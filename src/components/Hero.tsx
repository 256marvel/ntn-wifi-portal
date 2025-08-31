import { Button } from '@/components/ui/button';
import { ArrowRight, Wifi, Zap, Shield } from 'lucide-react';
import heroImage from '@/assets/hero-bg.jpg';

const Hero = () => {
  const scrollToPackages = () => {
    const element = document.getElementById('packages');
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  };

  const scrollToContact = () => {
    const element = document.getElementById('contact');
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
  };

  return (
    <section id="home" className="relative min-h-screen flex items-center justify-center overflow-hidden">
      {/* Background Image with Overlay */}
      <div className="absolute inset-0">
        <img 
          src={heroImage} 
          alt="High-speed internet connectivity" 
          className="w-full h-full object-cover"
        />
        <div className="absolute inset-0 bg-gradient-to-r from-primary/90 to-primary-dark/80"></div>
      </div>

      {/* Content */}
      <div className="relative z-10 container mx-auto px-4 text-center text-white">
        <div className="max-w-4xl mx-auto animate-fade-in">
          {/* Main Headline */}
          <h1 className="text-xl sm:text-2xl md:text-3xl lg:text-4xl xl:text-5xl font-bold mb-3 sm:mb-4 leading-tight px-2 sm:px-0">
            Stay Connected with{' '}
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-white to-blue-200">
              NTENJERU WIFI
            </span>
          </h1>

          {/* Subheading */}
          <p className="text-sm sm:text-base md:text-lg mb-4 sm:mb-6 text-blue-100 max-w-xl mx-auto leading-relaxed px-4 sm:px-2">
            Reliable Internet Anytime, Anywhere in Mukono. 
            Fast, affordable, and always available connectivity for work, study, and entertainment.
          </p>

          {/* Feature Highlights */}
          <div className="flex flex-wrap justify-center gap-2 sm:gap-4 mb-6 sm:mb-8 px-2 sm:px-0">
            <div className="flex items-center space-x-1 sm:space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-2 sm:px-3 py-1 sm:py-2">
              <Zap className="w-3 h-3 sm:w-4 sm:h-4 text-yellow-300" />
              <span className="text-xs font-medium">Lightning Fast</span>
            </div>
            <div className="flex items-center space-x-1 sm:space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-2 sm:px-3 py-1 sm:py-2">
              <Shield className="w-3 h-3 sm:w-4 sm:h-4 text-green-300" />
              <span className="text-xs font-medium">24/7 Available</span>
            </div>
            <div className="flex items-center space-x-1 sm:space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-2 sm:px-3 py-1 sm:py-2">
              <Wifi className="w-3 h-3 sm:w-4 sm:h-4 text-blue-300" />
              <span className="text-xs font-medium">Reliable Connection</span>
            </div>
          </div>

          {/* CTA Buttons */}
          <div className="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-center items-center px-4 sm:px-0 max-w-sm sm:max-w-none mx-auto">
            <Button 
              onClick={scrollToPackages}
              size="default" 
              className="w-full sm:w-auto bg-white text-primary hover:bg-blue-50 font-semibold text-sm px-4 sm:px-6 py-2 sm:py-3 rounded-full shadow-hero transition-all duration-300 hover:shadow-2xl hover:transform hover:-translate-y-1"
            >
              Get Started
              <ArrowRight className="ml-2 w-3 h-3 sm:w-4 sm:h-4" />
            </Button>
            <Button 
              onClick={scrollToPackages}
              variant="outline" 
              size="default"
              className="w-full sm:w-auto border-2 border-white text-white hover:bg-white hover:text-primary font-semibold text-sm px-4 sm:px-6 py-2 sm:py-3 rounded-full transition-all duration-300"
            >
              View Packages
            </Button>
          </div>

          {/* Trust Indicators */}
          <div className="mt-8 sm:mt-12 grid grid-cols-3 gap-4 sm:gap-6 max-w-2xl mx-auto px-4 sm:px-0">
            <div className="text-center">
              <div className="text-lg sm:text-2xl font-bold text-white mb-1">99.9%</div>
              <div className="text-blue-200 text-xs">Uptime Guarantee</div>
            </div>
            <div className="text-center">
              <div className="text-lg sm:text-2xl font-bold text-white mb-1">1000+</div>
              <div className="text-blue-200 text-xs">Happy Customers</div>
            </div>
            <div className="text-center">
              <div className="text-lg sm:text-2xl font-bold text-white mb-1">24/7</div>
              <div className="text-blue-200 text-xs">Technical Support</div>
            </div>
          </div>
        </div>
      </div>

      {/* Animated Background Elements */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute top-1/4 left-1/4 w-64 h-64 bg-white/5 rounded-full blur-3xl animate-pulse"></div>
        <div className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-blue-300/10 rounded-full blur-3xl animate-pulse delay-1000"></div>
      </div>
    </section>
  );
};

export default Hero;