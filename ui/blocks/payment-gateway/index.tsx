/**
 * External dependencies
 */
import React from 'react';
import { __ } from '@wordpress/i18n';
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { decodeEntities } from '@wordpress/html-entities';

/**
 * Internal dependencies
 */
import { CustomPaymentGatewayLabel } from './components/label';
import { CustomPaymentGatewayContent } from './components/content';
import type { GatewaySettings } from './types';

type PaymentMethodRegistrationOptions = Parameters<typeof registerPaymentMethod>[0];

/**
 * Get all custom gateway settings
 *
 * WooCommerce provides each payment method's data via window.wc.wcSettings.getSetting
 * using the pattern: getSetting('{paymentMethodId}_data')
 *
 * Gateway IDs are exposed via wp_localize_script (wooCustomGatewayBlocks.ids) from PHP
 * to avoid relying solely on enumerating wcSettings keys.
 */
const getCustomGateways = (): Record<string, GatewaySettings> => {
    const gateways: Record<string, GatewaySettings> = {};

    const wcSettings = (window as any).wc?.wcSettings;
    const localizedIds: string[] = (window as any).wooCustomGatewayBlocks?.ids || [];

    if (!wcSettings || typeof wcSettings.getSetting !== 'function') {
        return gateways;
    }

    // Use ids passed from PHP (wp_localize_script)
    const idsToLoad = localizedIds;

    idsToLoad.forEach((id) => {
        const key = `${id}_data`;
        if (key.startsWith('woocg-') && key.endsWith('_data')) {
            const settings = wcSettings.getSetting(key) as GatewaySettings;
            if (settings) {
                gateways[id] = settings;
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

        const paymentMethod: PaymentMethodRegistrationOptions = {
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
