<?php
/**
 * See LICENSE.md for license details.
 */
declare(strict_types=1);

namespace Dhl\Sdk\Paket\Bcs\Api;

use Dhl\Sdk\Paket\Bcs\Api\Data\AuthenticationStorageInterface;
use Psr\Log\LoggerInterface;

/**
 * Interface ServiceFactoryInterface
 *
 * @package Dhl\Sdk\Paket\Bcs\Api
 * @author  Christoph Aßmann <christoph.assmann@netresearch.de>
 * @link    https://www.netresearch.de/
 */
interface ServiceFactoryInterface
{
    const BASE_URL_PRODUCTION = 'https://cig.dhl.de/services/production/soap';
    const BASE_URL_SANDBOX = 'https://cig.dhl.de/services/sandbox/soap';

    /**
     * Create the shipment service able to perform shipment operations (create, delete).
     *
     * @param AuthenticationStorageInterface $authStorage
     * @param LoggerInterface $logger
     * @param bool $sandboxMode
     * @return ShipmentServiceInterface
     */
    public function createShipmentService(
        AuthenticationStorageInterface $authStorage,
        LoggerInterface $logger,
        bool $sandboxMode = false
    ): ShipmentServiceInterface;
}
