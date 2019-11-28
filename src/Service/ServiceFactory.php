<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Bcs\Service;

use Dhl\Sdk\Paket\Bcs\Api\Data\AuthenticationStorageInterface;
use Dhl\Sdk\Paket\Bcs\Api\ServiceFactoryInterface;
use Dhl\Sdk\Paket\Bcs\Api\ShipmentServiceInterface;
use Dhl\Sdk\Paket\Bcs\Exception\ServiceException;
use Dhl\Sdk\Paket\Bcs\Serializer\ClassMap;
use Dhl\Sdk\Paket\Bcs\Soap\SoapServiceFactory;
use Psr\Log\LoggerInterface;

/**
 * Class ServiceFactory
 *
 * @author  Christoph Aßmann <christoph.assmann@netresearch.de>
 * @link    https://www.netresearch.de/
 */
class ServiceFactory implements ServiceFactoryInterface
{
    public function createShipmentService(
        AuthenticationStorageInterface $authStorage,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): ShipmentServiceInterface {
        $wsdl = sprintf(
            '%s/%s/%s',
            'https://cig.dhl.de/cig-wsdls/com/dpdhl/wsdl/geschaeftskundenversand-api',
            '3.0',
            'geschaeftskundenversand-api-3.0.wsdl'
        );

        $options = [
            'trace' => 1,
            'features' => SOAP_SINGLE_ELEMENT_ARRAYS,
            'classmap' => ClassMap::get(),
            'login' => $authStorage->getApplicationId(),
            'password' => $authStorage->getApplicationToken(),
        ];

        if ($sandboxMode) {
            // override wsdl's default service location
            $options['location'] = self::BASE_URL_SANDBOX;
        }

        try {
            $soapClient = new \SoapClient($wsdl, $options);
        } catch (\SoapFault $soapFault) {
            throw new ServiceException($soapFault->getMessage(), $soapFault->getCode(), $soapFault);
        }

        $soapServiceFactory = new SoapServiceFactory($soapClient);
        $shipmentService = $soapServiceFactory->createShipmentService($authStorage, $logger, $sandboxMode);

        return $shipmentService;
    }
}
