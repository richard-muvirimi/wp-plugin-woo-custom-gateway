/**
 * External dependencies
 */
import React from 'react';
import { __ } from '@wordpress/i18n';
import { registerPaymentMethod, type PaymentMethodInterface } from '@woocommerce/blocks-registry';
import { decodeEntities } from '@wordpress/html-entities';

/**
 * Internal dependencies
 */
import { CustomPaymentGatewayLabel } from './components/label';
import { CustomPaymentGatewayContent } from './components/content';
import type { GatewaySettings } from './types';

/**
 * Get all custom gateway settings
 * 
 * WooCommerce provides each payment method's data via window.wc.wcSettings.getSetting
 * using the pattern: getSetting('{paymentMethodId}_data')
 */
const getCustomGateways = (): Record<string, GatewaySettings> => {
    const gateways: Record<string, GatewaySettings> = {};
    
    // Access WooCommerce settings from global window object
    const wcSettings = (window as any).wc?.wcSettings;
    if (!wcSettings || typeof wcSettings.getSetting !== 'function') {
        return gateways;
    }
    
    // Get all settings to find our gateway data keys
    // They follow the pattern: woocg-{id}_data
    const allSettings = wcSettings.getSettings() || {};
    
    Object.keys(allSettings).forEach((key) => {
        // Check if this is a custom gateway data key (starts with woocg- and ends with _data)
        if (key.startsWith('woocg-') && key.endsWith('_data')) {
            // Extract gateway ID by removing '_data' suffix
            const gatewayId = key.replace('_data', '');
            const settings = wcSettings.getSetting(key) as GatewaySettings;
            if (settings) {
                gateways[gatewayId] = settings;
            }
        }
    });
    
    return gateways;
};

/**
 * Register all custom payment gateways
 */
const registerCustomGateways = () => {
    const customGateways = getCustomGateways();
    
    Object.entries(customGateways).forEach(([gatewayId, settings]) => {
        // Create a wrapper component that injects gateway-specific props
        const ContentWrapper = (props: any) => (
            <CustomPaymentGatewayContent 
                {...props}
                description={settings.description}
                hasFields={settings.hasFields}
                gatewayId={gatewayId}
            />
        );

        const paymentMethod: PaymentMethodInterface = {
            name: gatewayId,
            label: <CustomPaymentGatewayLabel 
                title={decodeEntities(settings.title)} 
                icon={settings.icon}
            />,
            content: <ContentWrapper />,
            edit: <CustomPaymentGatewayContent 
                description={settings.description}
                hasFields={settings.hasFields}
                gatewayId={gatewayId}
            />,
            canMakePayment: () => true,
            ariaLabel: decodeEntities(settings.title),
            supports: {
                features: settings.supports,
            },
        };
        
        registerPaymentMethod(paymentMethod);
    });
};

// Register all custom gateways on load
registerCustomGateways();
