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
        <div className="text-center mb-8 sm:mb-12 animate-slide-up px-4 sm:px-0">
          <h2 className="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold text-foreground mb-2 sm:mb-3">
            Choose Your Perfect Plan
          </h2>
          <p className="text-sm sm:text-base md:text-lg text-muted-foreground max-w-xl mx-auto">
            Affordable internet packages designed for every need. Pay securely with MTN or Airtel Mobile Money.
          </p>
        </div>

        {/* Pricing Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 max-w-6xl mx-auto px-2 sm:px-4">
          {plans.map((plan, index) => {
            const IconComponent = plan.icon;
            return (
              <Card 
                key={plan.id} 
                className={`pricing-card relative ${plan.popular ? 'border-primary ring-2 ring-primary/20' : 'border-border'} animate-slide-up overflow-visible`}
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                {plan.popular && (
                  <div className="absolute -top-3 left-1/2 transform -translate-x-1/2 z-10">
                    <span className="bg-gradient-to-r from-primary to-primary-dark text-white px-3 py-1 rounded-full text-xs sm:text-sm font-semibold shadow-lg whitespace-nowrap">
                      Most Popular
                    </span>
                  </div>
                )}

                <CardHeader className="text-center pb-4 sm:pb-6 pt-6">
                  <div className="w-12 h-12 sm:w-16 sm:h-16 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <IconComponent className="w-6 h-6 sm:w-8 sm:h-8 text-primary" />
                  </div>
                  <CardTitle className="text-lg sm:text-xl md:text-2xl font-bold text-foreground">{plan.name}</CardTitle>
                  <CardDescription className="text-sm text-muted-foreground">Perfect for {plan.period} usage</CardDescription>
                  
                  <div className="mt-4 sm:mt-6">
                    <div className="flex items-baseline justify-center">
                      <span className="text-2xl sm:text-3xl md:text-4xl font-bold text-foreground">{plan.price}</span>
                      <span className="text-base sm:text-lg text-muted-foreground ml-2">{plan.currency}</span>
                    </div>
                    <p className="text-xs sm:text-sm text-muted-foreground mt-1 sm:mt-2">for {plan.period}</p>
                  </div>
                </CardHeader>

                <CardContent className="px-4 sm:px-6">
                  {/* Features List */}
                  <ul className="space-y-2 mb-6">
                    {plan.features.map((feature, featureIndex) => (
                      <li key={featureIndex} className="flex items-center text-xs sm:text-sm">
                        <Check className="w-3 h-3 sm:w-4 sm:h-4 text-primary mr-2 sm:mr-3 flex-shrink-0" />
                        <span className="text-muted-foreground">{feature}</span>
                      </li>
                    ))}
                  </ul>

                  {/* Payment Buttons */}
                  <div className="space-y-2 sm:space-y-3">
                    <Button 
                      onClick={() => {
                        console.log('MTN Button clicked', plan.name, plan.price, plan.currency);
                        handlePayment(plan.name, plan.price, plan.currency, 'MTN');
                      }}
                      className="w-full bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-semibold py-2 sm:py-3 text-sm rounded-lg transition-all duration-300 shadow-button hover:shadow-hero"
                    >
                      <Smartphone className="w-3 h-3 sm:w-4 sm:h-4 mr-2" />
                      Pay with MTN
                    </Button>
                    
                    <Button 
                      onClick={() => {
                        console.log('Airtel Button clicked', plan.name, plan.price, plan.currency);
                        handlePayment(plan.name, plan.price, plan.currency, 'Airtel');
                      }}
                      className="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold py-2 sm:py-3 text-sm rounded-lg transition-all duration-300 shadow-button hover:shadow-hero"
                    >
                      <Smartphone className="w-3 h-3 sm:w-4 sm:h-4 mr-2" />
                      Pay with Airtel
                    </Button>
                  </div>

                  <p className="text-xs text-muted-foreground text-center mt-3 sm:mt-4">
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