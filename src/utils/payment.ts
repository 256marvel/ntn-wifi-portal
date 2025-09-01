export interface PaymentRequest {
  phone: string;
  amount: string;
  plan: string;
  provider: 'MTN' | 'Airtel';
}

export interface PaymentResponse {
  success: boolean;
  message: string;
  referenceId?: string;
  externalId?: string;
}

export const processPayment = async (paymentData: PaymentRequest): Promise<PaymentResponse> => {
  try {
    // Validate phone number format
    const phoneRegex = /^(\+256|0)?[0-9]{9}$/;
    if (!phoneRegex.test(paymentData.phone)) {
      return {
        success: false,
        message: 'Please enter a valid phone number (e.g., 0701234567)'
      };
    }

    // Normalize phone number to international format
    let normalizedPhone = paymentData.phone.replace(/^\+/, '').replace(/^0/, '256');
    if (!normalizedPhone.startsWith('256')) {
      normalizedPhone = '256' + normalizedPhone;
    }

    // Determine the correct endpoint based on provider - use WordPress-compatible URLs
    const baseUrl = window.location.origin;
    const endpoint = paymentData.provider === 'MTN' ? 
      `${baseUrl}/wp-content/themes/ntenjeru-wifi/momo_request.php` : 
      `${baseUrl}/wp-content/themes/ntenjeru-wifi/airtel_request.php`;
    
    const response = await fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        phone: normalizedPhone,
        amount: paymentData.amount,
        plan: paymentData.plan
      })
    });

    const result = await response.text();
    
    try {
      const jsonResult = JSON.parse(result);
      return {
        success: jsonResult.success || response.ok,
        message: jsonResult.message || 'Payment request sent successfully',
        referenceId: jsonResult.referenceId,
        externalId: jsonResult.externalId
      };
    } catch {
      // If response is not JSON, check if it contains success indicators
      const isSuccess = result.includes('success') || result.includes('pending') || response.ok;
      return {
        success: isSuccess,
        message: isSuccess ? 'Payment request sent successfully' : result || 'Payment failed'
      };
    }
  } catch (error) {
    console.error('Payment processing error:', error);
    return {
      success: false,
      message: 'Network error. Please check your connection and try again.'
    };
  }
};

export const checkPaymentStatus = async (referenceId: string): Promise<PaymentResponse> => {
  try {
    const baseUrl = window.location.origin;
    const response = await fetch(`${baseUrl}/wp-content/themes/ntenjeru-wifi/check_payment_status.php`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        referenceId
      })
    });

    const result = await response.text();
    
    try {
      const jsonResult = JSON.parse(result);
      return {
        success: jsonResult.success || response.ok,
        message: jsonResult.message || jsonResult.status || 'Payment status retrieved'
      };
    } catch {
      return {
        success: response.ok,
        message: result || 'Status check completed'
      };
    }
  } catch (error) {
    console.error('Payment status check error:', error);
    return {
      success: false,
      message: 'Unable to check payment status'
    };
  }
};