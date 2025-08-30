import { useState } from 'react';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Smartphone, Loader2, CheckCircle, XCircle } from 'lucide-react';
import { useToast } from '@/hooks/use-toast';
import { processPayment, checkPaymentStatus, type PaymentRequest } from '@/utils/payment';

interface PaymentModalProps {
  isOpen: boolean;
  onClose: () => void;
  plan: {
    name: string;
    price: string;
    currency: string;
  } | null;
  provider: 'MTN' | 'Airtel' | null;
}

export const PaymentModal = ({ isOpen, onClose, plan, provider }: PaymentModalProps) => {
  const [phone, setPhone] = useState('');
  const [isProcessing, setIsProcessing] = useState(false);
  const [paymentStatus, setPaymentStatus] = useState<'idle' | 'processing' | 'success' | 'error'>('idle');
  const [statusMessage, setStatusMessage] = useState('');
  const [referenceId, setReferenceId] = useState<string | null>(null);
  const { toast } = useToast();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!plan || !provider) return;

    setIsProcessing(true);
    setPaymentStatus('processing');
    setStatusMessage('Initiating payment request...');

    const paymentData: PaymentRequest = {
      phone,
      amount: plan.price.replace(/,/g, ''),
      plan: plan.name,
      provider
    };

    try {
      const result = await processPayment(paymentData);
      
      if (result.success) {
        setPaymentStatus('success');
        setStatusMessage('Payment request sent! Please check your phone for the mobile money prompt.');
        setReferenceId(result.referenceId || null);
        
        toast({
          title: "Payment Request Sent",
          description: `Check your ${provider} mobile money for payment prompt`,
          duration: 5000,
        });

        // Auto-close after 3 seconds on success
        setTimeout(() => {
          handleClose();
        }, 3000);
      } else {
        setPaymentStatus('error');
        setStatusMessage(result.message);
        
        toast({
          title: "Payment Failed",
          description: result.message,
          variant: "destructive",
          duration: 5000,
        });
      }
    } catch (error) {
      setPaymentStatus('error');
      setStatusMessage('An unexpected error occurred. Please try again.');
      
      toast({
        title: "Error",
        description: "An unexpected error occurred. Please try again.",
        variant: "destructive",
        duration: 5000,
      });
    } finally {
      setIsProcessing(false);
    }
  };

  const handleClose = () => {
    setPhone('');
    setPaymentStatus('idle');
    setStatusMessage('');
    setReferenceId(null);
    setIsProcessing(false);
    onClose();
  };

  const checkStatus = async () => {
    if (!referenceId) return;
    
    setIsProcessing(true);
    const result = await checkPaymentStatus(referenceId);
    setStatusMessage(result.message);
    setIsProcessing(false);
    
    toast({
      title: "Status Updated",
      description: result.message,
      duration: 3000,
    });
  };

  if (!plan || !provider) return null;

  const providerColor = provider === 'MTN' ? 'from-yellow-500 to-yellow-600' : 'from-red-500 to-red-600';
  const providerColorHover = provider === 'MTN' ? 'from-yellow-600 to-yellow-700' : 'from-red-600 to-red-700';

  return (
    <Dialog open={isOpen} onOpenChange={handleClose}>
      <DialogContent className="sm:max-w-md">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2">
            <Smartphone className="w-5 h-5" />
            Pay with {provider} Mobile Money
          </DialogTitle>
        </DialogHeader>

        <div className="space-y-4">
          {/* Plan Details */}
          <div className="bg-muted rounded-lg p-4">
            <div className="text-sm text-muted-foreground">Selected Plan</div>
            <div className="font-semibold">{plan.name}</div>
            <div className="text-lg font-bold text-primary">
              {plan.price} {plan.currency}
            </div>
          </div>

          {paymentStatus === 'idle' && (
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <Label htmlFor="phone">Phone Number</Label>
                <Input
                  id="phone"
                  type="tel"
                  placeholder="e.g., 0701234567"
                  value={phone}
                  onChange={(e) => setPhone(e.target.value)}
                  required
                  className="mt-1"
                />
                <p className="text-xs text-muted-foreground mt-1">
                  Enter your {provider} mobile money number
                </p>
              </div>

              <div className="flex gap-2">
                <Button
                  type="submit"
                  disabled={isProcessing}
                  className={`flex-1 bg-gradient-to-r ${providerColor} hover:${providerColorHover} text-white`}
                >
                  {isProcessing ? (
                    <>
                      <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                      Processing...
                    </>
                  ) : (
                    <>
                      <Smartphone className="w-4 h-4 mr-2" />
                      Pay Now
                    </>
                  )}
                </Button>
                <Button type="button" variant="outline" onClick={handleClose}>
                  Cancel
                </Button>
              </div>
            </form>
          )}

          {/* Payment Status Display */}
          {paymentStatus !== 'idle' && (
            <div className="text-center space-y-4">
              {paymentStatus === 'processing' && (
                <div className="flex flex-col items-center">
                  <Loader2 className="w-8 h-8 animate-spin text-primary mb-2" />
                  <p className="text-sm">{statusMessage}</p>
                </div>
              )}

              {paymentStatus === 'success' && (
                <div className="flex flex-col items-center text-green-600">
                  <CheckCircle className="w-8 h-8 mb-2" />
                  <p className="text-sm font-medium">{statusMessage}</p>
                  {referenceId && (
                    <div className="mt-4 space-y-2">
                      <p className="text-xs text-muted-foreground">
                        Reference: {referenceId}
                      </p>
                      <Button
                        size="sm"
                        variant="outline"
                        onClick={checkStatus}
                        disabled={isProcessing}
                      >
                        {isProcessing ? (
                          <Loader2 className="w-4 h-4 mr-2 animate-spin" />
                        ) : null}
                        Check Status
                      </Button>
                    </div>
                  )}
                </div>
              )}

              {paymentStatus === 'error' && (
                <div className="flex flex-col items-center text-red-600">
                  <XCircle className="w-8 h-8 mb-2" />
                  <p className="text-sm font-medium">{statusMessage}</p>
                  <Button
                    className="mt-4"
                    onClick={() => {
                      setPaymentStatus('idle');
                      setStatusMessage('');
                    }}
                  >
                    Try Again
                  </Button>
                </div>
              )}
            </div>
          )}
        </div>
      </DialogContent>
    </Dialog>
  );
};