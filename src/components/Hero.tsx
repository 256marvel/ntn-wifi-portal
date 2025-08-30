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
          <h1 className="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold mb-4 sm:mb-6 leading-tight">
            Stay Connected with{' '}
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-white to-blue-200">
              NTENJERU WIFI
            </span>
          </h1>

          {/* Subheading */}
          <p className="text-base sm:text-lg md:text-xl mb-6 sm:mb-8 text-blue-100 max-w-2xl mx-auto leading-relaxed px-4 sm:px-0">
            Reliable Internet Anytime, Anywhere in Mukono. 
            Fast, affordable, and always available connectivity for work, study, and entertainment.
          </p>

          {/* Feature Highlights */}
          <div className="flex flex-wrap justify-center gap-3 sm:gap-6 mb-8 sm:mb-10 px-4 sm:px-0">
            <div className="flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-3 sm:px-4 py-2">
              <Zap className="w-4 h-4 sm:w-5 sm:h-5 text-yellow-300" />
              <span className="text-xs sm:text-sm font-medium">Lightning Fast</span>
            </div>
            <div className="flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-3 sm:px-4 py-2">
              <Shield className="w-4 h-4 sm:w-5 sm:h-5 text-green-300" />
              <span className="text-xs sm:text-sm font-medium">24/7 Available</span>
            </div>
            <div className="flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-full px-3 sm:px-4 py-2">
              <Wifi className="w-4 h-4 sm:w-5 sm:h-5 text-blue-300" />
              <span className="text-xs sm:text-sm font-medium">Reliable Connection</span>
            </div>
          </div>

          {/* CTA Buttons */}
          <div className="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center px-4 sm:px-0">
            <Button 
              onClick={scrollToPackages}
              size="lg" 
              className="w-full sm:w-auto bg-white text-primary hover:bg-blue-50 font-semibold text-sm sm:text-lg px-6 sm:px-8 py-3 sm:py-4 rounded-full shadow-hero transition-all duration-300 hover:shadow-2xl hover:transform hover:-translate-y-1"
            >
              Get Started
              <ArrowRight className="ml-2 w-4 h-4 sm:w-5 sm:h-5" />
            </Button>
            <Button 
              onClick={scrollToPackages}
              variant="outline" 
              size="lg"
              className="w-full sm:w-auto border-2 border-white text-white hover:bg-white hover:text-primary font-semibold text-sm sm:text-lg px-6 sm:px-8 py-3 sm:py-4 rounded-full transition-all duration-300"
            >
              View Packages
            </Button>
          </div>

          {/* Trust Indicators */}
          <div className="mt-12 sm:mt-16 grid grid-cols-1 sm:grid-cols-3 gap-6 sm:gap-8 max-w-3xl mx-auto px-4 sm:px-0">
            <div className="text-center">
              <div className="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">99.9%</div>
              <div className="text-blue-200 text-xs sm:text-sm">Uptime Guarantee</div>
            </div>
            <div className="text-center">
              <div className="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">1000+</div>
              <div className="text-blue-200 text-xs sm:text-sm">Happy Customers</div>
            </div>
            <div className="text-center">
              <div className="text-2xl sm:text-3xl font-bold text-white mb-1 sm:mb-2">24/7</div>
              <div className="text-blue-200 text-xs sm:text-sm">Technical Support</div>
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