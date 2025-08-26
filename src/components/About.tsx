import { Button } from '@/components/ui/button';
import { ArrowRight, Target, Users, Award } from 'lucide-react';

const About = () => {
  const scrollToContact = () => {
    const element = document.getElementById('contact');
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  };

  const stats = [
    {
      icon: Users,
      number: '1000+',
      label: 'Happy Customers',
      color: 'text-blue-600'
    },
    {
      icon: Award,
      number: '99.9%',
      label: 'Uptime Guarantee',
      color: 'text-green-600'
    },
    {
      icon: Target,
      number: '24/7',
      label: 'Technical Support',
      color: 'text-purple-600'
    }
  ];

  return (
    <section id="about" className="py-20 bg-gradient-to-b from-muted to-background">
      <div className="container mx-auto px-4">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
          {/* Content */}
          <div className="animate-slide-up">
            <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-6">
              About NTENJERU WIFI
            </h2>
            
            <div className="prose prose-lg text-muted-foreground mb-8">
              <p className="text-xl leading-relaxed mb-6">
                NTENJERU WIFI provides affordable and reliable internet connectivity throughout 
                Mukono, Uganda. We believe that everyone deserves access to fast, dependable 
                internet for work, education, and entertainment.
              </p>
              
              <p className="text-lg leading-relaxed mb-6">
                Our mission is simple: keep you connected with quality service at prices that 
                make sense. We focus on simplicity, speed, and customer satisfaction, ensuring 
                that your internet experience is seamless and hassle-free.
              </p>

              <p className="text-lg leading-relaxed">
                Whether you're a student, professional, or family looking to stay connected, 
                we have the perfect package for your needs. Join thousands of satisfied 
                customers who trust NTENJERU WIFI for their internet connectivity.
              </p>
            </div>

            <Button 
              onClick={scrollToContact}
              size="lg" 
              className="bg-gradient-primary text-white hover:shadow-hero transition-all duration-300 font-semibold"
            >
              Get in Touch
              <ArrowRight className="ml-2 w-5 h-5" />
            </Button>
          </div>

          {/* Stats */}
          <div className="animate-slide-up delay-200">
            <div className="bg-white rounded-3xl p-8 shadow-card">
              <h3 className="text-2xl font-bold text-foreground mb-8 text-center">
                Our Impact
              </h3>
              
              <div className="space-y-8">
                {stats.map((stat, index) => {
                  const IconComponent = stat.icon;
                  return (
                    <div key={index} className="flex items-center space-x-4">
                      <div className={`w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center`}>
                        <IconComponent className={`w-6 h-6 ${stat.color}`} />
                      </div>
                      <div>
                        <div className="text-3xl font-bold text-foreground">{stat.number}</div>
                        <div className="text-muted-foreground">{stat.label}</div>
                      </div>
                    </div>
                  );
                })}
              </div>

              <div className="mt-8 pt-8 border-t border-border">
                <div className="text-center">
                  <p className="text-sm text-muted-foreground mb-2">Serving Mukono since 2020</p>
                  <div className="flex justify-center space-x-2">
                    {[1, 2, 3, 4, 5].map((star) => (
                      <div key={star} className="w-5 h-5 text-yellow-400">★</div>
                    ))}
                  </div>
                  <p className="text-xs text-muted-foreground mt-1">4.9/5 Customer Rating</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        {/* Mission Statement */}
        <div className="mt-20 text-center">
          <div className="max-w-4xl mx-auto bg-gradient-to-r from-primary/5 to-primary-light/20 rounded-3xl p-8 md:p-12">
            <h3 className="text-3xl font-bold text-foreground mb-4">Our Mission</h3>
            <p className="text-xl text-muted-foreground leading-relaxed">
              "To bridge the digital divide by providing affordable, reliable internet access 
              to every home and business in Mukono, empowering our community through connectivity."
            </p>
          </div>
        </div>
      </div>
    </section>
  );
};

export default About;