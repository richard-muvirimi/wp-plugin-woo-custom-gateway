/**
 * External dependencies
 */
import React, { useState, useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { RawHTML } from '@wordpress/element';
import { decodeEntities } from '@wordpress/html-entities';

/**
 * Internal dependencies
 */
import type { ContentProps } from '../types';

/**
 * Custom Payment Gateway Content Component
 * 
 * Displays gateway description and optional payment proof field
 */
export const CustomPaymentGatewayContent: React.FC<ContentProps> = ({ 
    description, 
    hasFields, 
    gatewayId,
    eventRegistration,
    emitResponse
}) => {
    const [paymentNote, setPaymentNote] = useState<string>('');
    
    // Match the field name format that PHP Gateway.php expects
    // PHP: Functions::get_plugin_slug('-note-' . $this->id)
    // Which becomes: 'woo-custom-gateway-note-woocg-{id}'
    const fieldName = `woo-custom-gateway-note-${gatewayId}`;

    const handleNoteChange = (value: string) => {
        setPaymentNote(value);
    };

    // Register payment setup callback to emit payment data to Store API
    useEffect(() => {
        if (!eventRegistration || !emitResponse) {
            return;
        }

        const { onPaymentSetup } = eventRegistration;
        const unsubscribe = onPaymentSetup(() => {
            // Emit payment data to be sent to backend via Store API
            return {
                type: emitResponse.responseTypes.SUCCESS,
                meta: {
                    paymentMethodData: {
                        [fieldName]: paymentNote,
                    },
                },
            };
        });

        // Cleanup subscription on unmount
        return () => {
            unsubscribe();
        };
    }, [eventRegistration, emitResponse, fieldName, paymentNote]);

    return (
        <div className="wc-block-components-payment-method-content">
            {description && (
                <div className="wc-block-components-payment-method-description">
                    <RawHTML>{decodeEntities(description)}</RawHTML>
                </div>
            )}
            
            {hasFields && (
                <div className="wc-block-components-payment-method-fields">
                    <div className="wc-block-components-text-input">
                        <textarea
                            id={fieldName}
                            className="wc-block-components-text-input__input"
                            rows={4}
                            placeholder={__('Enter your payment reference or proof of payment', 'woo-custom-gateway')}
                            onChange={(e) => handleNoteChange(e.target.value)}
                            value={paymentNote}
                            style={{
                                width: '100%',
                                padding: '8px',
                                border: '1px solid #ddd',
                                borderRadius: '4px',
                                fontFamily: 'inherit',
                                fontSize: 'inherit'
                            }}
                        />
                        <p className="wc-block-components-text-input__help-text">
                            {__('Provide any payment reference number or details to help us verify your payment.', 'woo-custom-gateway')}
                        </p>
                    </div>
                </div>
            )}
        </div>
    );
};
