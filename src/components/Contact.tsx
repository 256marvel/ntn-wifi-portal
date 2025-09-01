import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Phone, MessageCircle, Mail, MapPin, Clock, Send } from 'lucide-react';
import { useToast } from '@/hooks/use-toast';

const Contact = () => {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    message: ''
  });
  const { toast } = useToast();

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    toast({
      title: "Message Sent!",
      description: "Thank you for contacting us. We'll get back to you within 24 hours.",
      duration: 5000,
    });
    setFormData({ name: '', email: '', phone: '', message: '' });
  };

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const contactInfo = [
    {
      icon: Phone,
      title: 'Call Now',
      value: '+256 763 643724',
      description: 'Call us anytime for support',
      action: () => window.open('tel:+256763643724'),
      color: 'from-green-500 to-green-600'
    },
    {
      icon: MapPin,
      title: 'Location',
      value: 'Ntenjeru, Mukono',
      description: 'Uganda, East Africa',
      action: () => window.open('https://maps.google.com/?q=Ntenjeru,Uganda'),
      color: 'from-purple-500 to-purple-600'
    },
    {
      icon: Clock,
      title: 'Service Hours',
      value: '24/7 Available',
      description: 'Always here for you',
      action: () => {},
      color: 'from-orange-500 to-orange-600'
    }
  ];

  return (
    <section id="contact" className="py-20 bg-background">
      <div className="container mx-auto px-4">
        {/* Section Header */}
        <div className="text-center mb-16 animate-slide-up">
          <h2 className="text-4xl md:text-5xl font-bold text-foreground mb-4">
            Get in Touch
          </h2>
          <p className="text-xl text-muted-foreground max-w-2xl mx-auto">
            Ready to get connected? Contact us today and let's set up your perfect internet package.
          </p>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-12">
          {/* Contact Form */}
          <div className="animate-slide-up">
            <Card className="shadow-card">
              <CardHeader>
                <CardTitle className="text-2xl font-bold text-foreground">Send us a Message</CardTitle>
                <CardDescription>
                  Fill out the form below and we'll get back to you within 24 hours.
                </CardDescription>
              </CardHeader>
              <CardContent>
                <form onSubmit={handleSubmit} className="space-y-6">
                  <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label className="text-sm font-medium text-foreground mb-2 block">
                        Full Name *
                      </label>
                      <Input
                        name="name"
                        value={formData.name}
                        onChange={handleInputChange}
                        placeholder="Your full name"
                        required
                        className="w-full"
                      />
                    </div>
                    <div>
                      <label className="text-sm font-medium text-foreground mb-2 block">
                        Phone Number *
                      </label>
                      <Input
                        name="phone"
                        value={formData.phone}
                        onChange={handleInputChange}
                        placeholder="+256 XXX XXXXXX"
                        required
                        className="w-full"
                      />
                    </div>
                  </div>
                  
                  <div>
                    <label className="text-sm font-medium text-foreground mb-2 block">
                      Email Address
                    </label>
                    <Input
                      name="email"
                      type="email"
                      value={formData.email}
                      onChange={handleInputChange}
                      placeholder="your.email@example.com"
                      className="w-full"
                    />
                  </div>
                  
                  <div>
                    <label className="text-sm font-medium text-foreground mb-2 block">
                      Message *
                    </label>
                    <Textarea
                      name="message"
                      value={formData.message}
                      onChange={handleInputChange}
                      placeholder="Tell us about your internet needs or ask any questions..."
                      rows={5}
                      required
                      className="w-full resize-none"
                    />
                  </div>
                  
                  <Button 
                    type="submit" 
                    className="w-full bg-gradient-primary text-white hover:shadow-hero transition-all duration-300 font-semibold"
                    size="lg"
                  >
                    <Send className="w-5 h-5 mr-2" />
                    Send Message
                  </Button>
                </form>
              </CardContent>
            </Card>
          </div>

          {/* Contact Information */}
          <div className="animate-slide-up delay-200">
            <div className="space-y-6">
              {contactInfo.map((info, index) => {
                const IconComponent = info.icon;
                return (
                  <Card 
                    key={index}
                    className="cursor-pointer hover:shadow-card transition-all duration-300 hover:transform hover:-translate-y-1"
                    onClick={info.action}
                  >
                    <CardContent className="p-6">
                      <div className="flex items-center space-x-4">
                        <div className={`w-12 h-12 rounded-xl bg-gradient-to-br ${info.color} flex items-center justify-center shadow-lg`}>
                          <IconComponent className="w-6 h-6 text-white" />
                        </div>
                        <div className="flex-1">
                          <h3 className="font-semibold text-foreground text-lg">{info.title}</h3>
                          <p className="text-primary font-medium">{info.value}</p>
                          <p className="text-sm text-muted-foreground">{info.description}</p>
                        </div>
                      </div>
                    </CardContent>
                  </Card>
                );
              })}
            </div>

            {/* Call CTA */}
            <div className="mt-8">
              <Card className="bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-hero">
                <CardContent className="p-6 text-center">
                  <Phone className="w-12 h-12 mx-auto mb-4" />
                  <h3 className="text-xl font-bold mb-2">Need Instant Help?</h3>
                  <p className="mb-4 opacity-90">
                    Call us directly for immediate assistance with your internet connection.
                  </p>
                  <Button 
                    onClick={() => window.open('tel:+256763643724')}
                    variant="secondary"
                    className="bg-white text-blue-600 hover:bg-gray-100 font-semibold"
                  >
                    <Phone className="w-5 h-5 mr-2" />
                    Call Now
                  </Button>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>

        {/* Google Maps */}
        <div className="mt-16 animate-slide-up">
          <Card className="shadow-card overflow-hidden">
            <CardHeader>
              <CardTitle className="text-2xl font-bold text-foreground text-center">
                Find Us in Ntenjeru
              </CardTitle>
              <CardDescription className="text-center">
                Located in the heart of Mukono, serving the entire Ntenjeru community
              </CardDescription>
            </CardHeader>
            <CardContent className="p-0">
              <div className="h-64 md:h-96 bg-muted rounded-b-lg flex items-center justify-center">
                <div className="text-center">
                  <MapPin className="w-16 h-16 text-primary mx-auto mb-4" />
                  <h3 className="text-xl font-semibold text-foreground mb-2">Ntenjeru, Mukono</h3>
                  <p className="text-muted-foreground mb-4">Uganda, East Africa</p>
                  <Button 
                    onClick={() => window.open('https://maps.google.com/?q=Ntenjeru,Uganda')}
                    variant="outline"
                  >
                    View on Google Maps
                  </Button>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </section>
  );
};

export default Contact;