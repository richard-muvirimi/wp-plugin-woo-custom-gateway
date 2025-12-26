/**
 * External dependencies
 */
import React from 'react';

/**
 * Internal dependencies
 */
import type { LabelProps } from '../types';

/**
 * Custom Payment Gateway Label Component
 * 
 * Displays the gateway title and optional icon in checkout
 */
export const CustomPaymentGatewayLabel: React.FC<LabelProps> = ({ title, icon }) => {
    return (
        <span className="wc-block-components-payment-method-label">
            {icon && (
                <img 
                    src={icon} 
                    alt={title} 
                    className="wc-block-components-payment-method-label__icon"
                    style={{ 
                        maxHeight: '24px', 
                        marginRight: '8px',
                        verticalAlign: 'middle'
                    }}
                />
            )}
            <span className="wc-block-components-payment-method-label__text">
                {title}
            </span>
        </span>
    );
};
