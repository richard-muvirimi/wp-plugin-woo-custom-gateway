/**
 * Type definitions for WooCommerce Custom Payment Gateway Blocks
 * 
 * @package WooCustomGateway
 */

/**
 * Label component props
 */
export interface LabelProps {
    title: string;
    icon?: string;
}

/**
 * Content component props
 */
export interface ContentProps {
    description?: string;
    hasFields?: boolean;
    gatewayId?: string;
    eventRegistration?: {
        onPaymentSetup: (callback: () => PaymentSetupResponse) => () => void;
    };
    emitResponse?: {
        responseTypes: {
            SUCCESS: string;
            ERROR: string;
            FAIL: string;
        };
    };
    [key: string]: any; // Allow other props from WooCommerce Blocks
}

/**
 * Payment setup response interface
 */
export interface PaymentSetupResponse {
    type: string;
    meta?: {
        paymentMethodData?: Record<string, unknown>;
    };
}

/**
 * Gateway settings interface
 */
export interface GatewaySettings {
    title: string;
    description: string;
    icon?: string;
    supports: string[];
    hasFields: boolean;
    gatewayId: string;
    postId: number;
}
