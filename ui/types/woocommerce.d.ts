/**
 * Type declarations for WooCommerce Blocks packages
 * 
 * These declarations provide TypeScript support for WooCommerce Blocks APIs
 * that are provided by WordPress at runtime and not bundled in our package.
 * The @woocommerce/dependency-extraction-webpack-plugin marks these as externals.
 */

declare module '@woocommerce/blocks-registry' {
    import type { ReactElement } from 'react';

    export interface PaymentMethodInterface {
        name: string;
        label: ReactElement | string;
        content: ReactElement;
        edit: ReactElement;
        canMakePayment: () => boolean;
        ariaLabel: string;
        supports?: {
            features?: string[];
            showSavedCards?: boolean;
            showSaveOption?: boolean;
        };
    }

    export function registerPaymentMethod(config: PaymentMethodInterface): void;
}
