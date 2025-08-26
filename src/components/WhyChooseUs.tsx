import { Clock, DollarSign, Wifi, HeadphonesIcon } from 'lucide-react';

const WhyChooseUs = () => {
  const features = [
    {
      icon: Clock,
      title: '24/7 Availability',
      description: 'Round-the-clock internet access with 99.9% uptime guarantee. Stay connected whenever you need it.',
      gradient: 'from-blue-500 to-blue-600'
    },
    {
      icon: DollarSign,
      title: 'Affordable Prices',
      description: 'Competitive pricing that fits your budget. Get premium internet without breaking the bank.',
      gradient: 'from-green-500 to-green-600'
    },
    {
      icon: Wifi,
      title: 'Reliable & Fast Connectivity',
      description: 'High-speed internet with consistent performance. Stream, work, and browse without interruptions.',
      gradient: 'from-purple-500 to-purple-600'
    },
    {
      icon: HeadphonesIcon,
      title: 'Friendly Customer Support',
      description: 'Dedicated support team ready to help. Quick response times and technical assistance when you need it.',
      gradient: 'from-orange-500 to-orange-600'
    }
  ];

  return (
    <section className="py-20 bg-background">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-16 animate-slide-up">
          <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
            Why Choose NTENJERU WIFI?
          </h2>
          <p className="text-xl text-muted-foreground max-w-2xl mx-auto">
            Experience the difference with our reliable, affordable, and customer-focused internet service.
          </p>
        </div>

        {/* Features Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {features.map((feature, index) => {
            const IconComponent = feature.icon;
            return (
              <div 
                key={index}
                className="group text-center animate-slide-up hover:transform hover:-translate-y-2 transition-all duration-300"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                {/* Icon Container */}
                <div className={`w-20 h-20 mx-auto mb-6 rounded-2xl bg-gradient-to-br ${feature.gradient} flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-300`}>
                  <IconComponent className="w-10 h-10 text-white" />
                </div>

                {/* Content */}
                <h3 className="text-xl font-semibold text-foreground mb-3 group-hover:text-primary transition-colors duration-300">
                  {feature.title}
                </h3>
                <p className="text-muted-foreground leading-relaxed">
                  {feature.description}
                </p>
              </div>
            );
          })}
        </div>

        {/* Bottom CTA */}
        <div className="text-center mt-16">
          <div className="inline-flex items-center space-x-2 bg-primary/10 text-primary px-6 py-3 rounded-full font-medium">
            <Wifi className="w-5 h-5" />
            <span>Trusted by 1000+ customers in Mukono</span>
          </div>
        </div>
      </div>
    </section>
  );
};

export default WhyChooseUs;