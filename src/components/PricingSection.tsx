import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Check, Clock, Calendar, Trophy, Smartphone } from 'lucide-react';
import { useState } from 'react';
import { useToast } from '@/hooks/use-toast';
import { PaymentModal } from '@/components/PaymentModal';

const PricingSection = () => {
  const [selectedPlan, setSelectedPlan] = useState<{name: string; price: string; currency: string} | null>(null);
  const [selectedProvider, setSelectedProvider] = useState<'MTN' | 'Airtel' | null>(null);
  const [isPaymentModalOpen, setIsPaymentModalOpen] = useState(false);
  const { toast } = useToast();

  const handlePayment = (planName: string, planPrice: string, planCurrency: string, provider: 'MTN' | 'Airtel') => {
    setSelectedPlan({ name: planName, price: planPrice, currency: planCurrency });
    setSelectedProvider(provider);
    setIsPaymentModalOpen(true);
  };

  const plans = [
    {
      id: 'daily',
      name: '24 Hours',
      price: '1,000',
      currency: 'UGX',
      period: '24 hours',
      icon: Clock,
      popular: false,
      features: [
        'High-speed internet access',
        '24-hour unlimited browsing',
        'Connect multiple devices',
        'Basic customer support',
        'Social media access',
        'Video streaming capability'
      ]
    },
    {
      id: 'weekly',
      name: '1 Week',
      price: '7,000',
      currency: 'UGX',
      period: '7 days',
      icon: Calendar,
      popular: true,
      features: [
        'High-speed internet access',
        'Full week unlimited browsing',
        'Connect unlimited devices',
        'Priority customer support',
        'HD video streaming',
        'File downloads & uploads',
        'Email and work applications',
        'Online gaming support'
      ]
    },
    {
      id: 'monthly',
      name: '1 Month',
      price: '25,000',
      currency: 'UGX',
      period: '30 days',
      icon: Trophy,
      popular: false,
      features: [
        'Maximum speed internet access',
        'Full month unlimited browsing',
        'Connect unlimited devices',
        '24/7 premium support',
        '4K video streaming',
        'Large file transfers',
        'Business applications',
        'Online gaming & streaming',
        'Technical support priority',
        'Speed guarantee'
      ]
    }
  ];

  return (
    <section id="packages" className="py-20 bg-gradient-to-b from-background to-muted">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-12 sm:mb-16 animate-slide-up px-4 sm:px-0">
          <h2 className="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold text-foreground mb-3 sm:mb-4">
            Choose Your Perfect Plan
          </h2>
          <p className="text-base sm:text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto">
            Affordable internet packages designed for every need. Pay securely with MTN or Airtel Mobile Money.
          </p>
        </div>

        {/* Pricing Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8 max-w-7xl mx-auto px-4 sm:px-0">
          {plans.map((plan, index) => {
            const IconComponent = plan.icon;
            return (
              <Card 
                key={plan.id} 
                className={`pricing-card relative ${plan.popular ? 'border-primary ring-2 ring-primary/20' : 'border-border'} animate-slide-up`}
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                {plan.popular && (
                  <div className="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span className="bg-gradient-primary text-white px-4 py-2 rounded-full text-sm font-semibold shadow-lg">
                      Most Popular
                    </span>
                  </div>
                )}

                <CardHeader className="text-center pb-6">
                  <div className="w-16 h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-4">
                    <IconComponent className="w-8 h-8 text-primary" />
                  </div>
                  <CardTitle className="text-2xl font-bold text-foreground">{plan.name}</CardTitle>
                  <CardDescription className="text-muted-foreground">Perfect for {plan.period} usage</CardDescription>
                  
                  <div className="mt-6">
                    <div className="flex items-baseline justify-center">
                      <span className="text-3xl sm:text-4xl md:text-5xl font-bold text-foreground">{plan.price}</span>
                      <span className="text-lg sm:text-xl text-muted-foreground ml-2">{plan.currency}</span>
                    </div>
                    <p className="text-sm text-muted-foreground mt-2">for {plan.period}</p>
                  </div>
                </CardHeader>

                <CardContent>
                  {/* Features List */}
                  <ul className="space-y-3 mb-8">
                    {plan.features.map((feature, featureIndex) => (
                      <li key={featureIndex} className="flex items-center text-sm">
                        <Check className="w-4 h-4 text-primary mr-3 flex-shrink-0" />
                        <span className="text-muted-foreground">{feature}</span>
                      </li>
                    ))}
                  </ul>

                  {/* Payment Buttons */}
                  <div className="space-y-3">
                    <Button 
                      onClick={() => handlePayment(plan.name, plan.price, plan.currency, 'MTN')}
                      className="w-full bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-semibold py-3 rounded-lg transition-all duration-300 shadow-button hover:shadow-hero"
                    >
                      <Smartphone className="w-4 h-4 mr-2" />
                      Pay with MTN
                    </Button>
                    
                    <Button 
                      onClick={() => handlePayment(plan.name, plan.price, plan.currency, 'Airtel')}
                      className="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-3 rounded-lg transition-all duration-300 shadow-button hover:shadow-hero"
                    >
                      <Smartphone className="w-4 h-4 mr-2" />
                      Pay with Airtel
                    </Button>
                  </div>

                  <p className="text-xs text-muted-foreground text-center mt-4">
                    Secure mobile money payment
                  </p>
                </CardContent>
              </Card>
            );
          })}
        </div>

        {/* Additional Info */}
        <div className="text-center mt-12">
          <p className="text-muted-foreground">
            All plans include unlimited data usage • No hidden fees • Instant activation after payment
          </p>
        </div>

        <PaymentModal
          isOpen={isPaymentModalOpen}
          onClose={() => setIsPaymentModalOpen(false)}
          plan={selectedPlan}
          provider={selectedProvider}
        />
      </div>
    </section>
  );
};

export default PricingSection;