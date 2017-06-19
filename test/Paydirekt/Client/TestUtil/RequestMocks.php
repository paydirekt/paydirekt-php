<?php


namespace Paydirekt\Client\TestUtil;

/**
 * A collection of example requests to create checkouts, captures and refunds.
 */
class RequestMocks
{
    public static function minimalCheckoutRequest()
    {
        return array(
            'type' => 'DIRECT_SALE',
            'totalAmount' => 14.00,
            'currency' => 'EUR',
            'shippingAddress' => array(
                'addresseeGivenName' => 'Hermann',
                'addresseeLastName' => 'Meyer',
                'street' => 'Wieseneckstraße',
                'streetNr' => '26',
                'zip' => '90571',
                'city' => 'Schwaig bei Nürnberg',
                'countryCode' => 'DE'
            ),
            'merchantOrderReferenceNumber' => 'order-A12223412',
            'redirectUrlAfterSuccess' => 'https://spielauto-versand.de/order/123/success',
            'redirectUrlAfterCancellation' => 'https://spielauto-versand.de/order/123/cancellation',
            'redirectUrlAfterRejection' => 'https://spielauto-versand.de/order/123/rejection'
        );
    }

    public static function orderCheckoutRequest()
    {
        return array(
            'type' => 'ORDER',
            'totalAmount' => 100.0,
            'shippingAmount' => 3.5,
            'orderAmount' => 96.5,
            'items' => array(
                array(
                    'quantity' => 3,
                    'name' => 'Bobbycar',
                    'ean' => '800001303',
                    'price' => '25.99'
                ),
                array(
                    'quantity' => 1,
                    'name' => 'Helm',
                    'price' => 18.53
                )
            ),
            'shoppingCartType' => 'PHYSICAL',
            'deliveryType' => 'STANDARD',
            'currency' => 'EUR',
            'shippingAddress' => array(
                'addresseeGivenName' => 'Marie',
                'addresseeLastName' => 'Mustermann',
                'street' => 'Packstation',
                'streetNr' => '999',
                'additionalAddressInformation' => '1234567890',
                'zip' => '90402',
                'city' => 'Schwaig',
                'countryCode' => 'DE'
            ),
            'merchantCustomerNumber' => 'cust-732477',
            'merchantOrderReferenceNumber' => 'order-A12223412',
            'merchantInvoiceReferenceNumber' => '20150112334345',
            'redirectUrlAfterSuccess' => 'https://spielauto-versand.de/order/123/success',
            'redirectUrlAfterCancellation' => 'https://spielauto-versand.de/order/123/cancellation',
            'redirectUrlAfterRejection' => 'https://spielauto-versand.de/order/123/rejection',
            'callbackUrlStatusUpdates' => 'https://spielauto-versand.de/callback/status'
        );
    }

    public static function directSaleCheckoutRequest()
    {
        return array(
            'type' => 'DIRECT_SALE',
            'totalAmount' => 100.0,
            'shippingAmount' => 3.5,
            'orderAmount' => 96.5,
            'items' => array(
                array(
                    'quantity' => 3,
                    'name' => 'Bobbycar',
                    'ean' => '800001303',
                    'price' => 25.99
                ),
                array(
                    'quantity' => 1,
                    'name' => 'Helm',
                    'price' => 18.53
                )
            ),
            'currency' => 'EUR',
            'overcapture' => false,
            'shippingAddress' => array(
                'addresseeGivenName' => 'Marie',
                'addresseeLastName' => 'Mustermann',
                'street' => 'Packstation',
                'streetNr' => '999',
                'additionalAddressInformation' => '1234567890',
                'zip' => '90402',
                'city' => 'Schwaig',
                'countryCode' => 'DE'
            ),
            'deliveryInformation' => array(
                'expectedShippingDate' => '2016-10-19T12:00:00.000Z',
                'logisticsProvider' => 'DHL',
                'trackingNumber' => '1234567890'
            ),
            'merchantCustomerNumber' => 'cust-732477',
            'merchantOrderReferenceNumber' => 'order-A12223412',
            'merchantReconciliationReferenceNumber' => 'recon-A12223412',
            'merchantInvoiceReferenceNumber' => '20150112334345',
            'note' => 'Ihr Einkauf bei Spielauto-Versand.',
            'sha256hashedEmailAddress' => 'fxP4R-IxH1Eaxpb0f_i5Shc8-FrYrtmx5lx35f9Xzgg',
            'minimumAge' => 18,
            'redirectUrlAfterSuccess' => 'https://spielauto-versand.de/order/123/success',
            'redirectUrlAfterCancellation' => 'https://spielauto-versand.de/order/123/cancellation',
            'redirectUrlAfterAgeVerificationFailure' => 'https://spielauto-versand.de/order/123/ageverificationfailed',
            'redirectUrlAfterRejection' => 'https://spielauto-versand.de/order/123/rejection',
            'callbackUrlStatusUpdates' => 'https://spielauto-versand.de/callback/status'
        );
    }

    public static function expressCheckoutRequest()
    {
        return array(
            'express' => true,
            'callbackUrlCheckDestinations' => 'https://spielauto-versand.de/destinations/check',
            'webUrlShippingTerms' => 'https://spielauto-versand.de/shippingterms',
            'type' => 'DIRECT_SALE',
            'totalAmount' => 100.0,
            'shippingAmount' => 3.5,
            'orderAmount' => 96.5,
            'currency' => 'EUR',
            'merchantCustomerNumber' => 'cust-732477',
            'merchantOrderReferenceNumber' => 'order-A12223412',
            'merchantReconciliationReferenceNumber' => 'recon-A12223412',
            'merchantInvoiceReferenceNumber' => '20150112334345',
            'note' => 'Ihr Einkauf bei Spielauto-Versand.',
            'minimumAge' => 18,
            'redirectUrlAfterSuccess' => 'https://spielauto-versand.de/order/123/success',
            'redirectUrlAfterCancellation' => 'https://spielauto-versand.de/order/123/cancellation',
            'redirectUrlAfterAgeVerificationFailure' => 'https://spielauto-versand.de/order/123/ageverificationfailed',
            'redirectUrlAfterRejection' => 'https://spielauto-versand.de/order/123/rejection'
        );
    }

    public static function captureRequest()
    {
        return array(
            'amount' => 18.53,
            'finalCapture' => false,
            'note' => 'Thanks for shopping.',
            'merchantCaptureReferenceNumber' => 'capture-21323',
            'merchantReconciliationReferenceNumber' => 'recon-1234',
            'captureInvoiceReferenceNumber' => 'invoice-1234',
            'callbackUrlStatusUpdates' => 'https://spielauto-versand.de/callback/status',
            'deliveryInformation' => array(
                'expectedShippingDate' => '2016-10-19T12:00:00.000Z',
                'logisticsProvider' => 'DHL',
                'trackingNumber' => '1234567890'
            )
        );
    }

    public static function refundRequest()
    {
        return array(
            'amount' => 18.53,
            'note' => 'Ihre Bestellung vom 31.03.2015',
            'merchantRefundReferenceNumber' => 'refund-99989',
            'merchantReconciliationReferenceNumber' => 'recon-1234',
            'reason' => 'MERCHANT_CAN_NOT_DELIVER_GOODS'
        );
    }
}