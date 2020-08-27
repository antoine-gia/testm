<?php

declare(strict_types=1);

namespace App\Controller\Partials;

use App\Service\CoinDeskClient;
use Money\Currency;
use Money\Money;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Tbbc\MoneyBundle\Formatter\MoneyFormatter;

class GetBitcoinRateValuePartialController extends AbstractController
{
    /**
     * @Route("_partials/bitcoin_rate_value", name="partials_bitcoin_rate_value")
     * @param CacheInterface $cache
     * @param CoinDeskClient $coinDeskClient
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function __invoke(
        CacheInterface $cache,
        CoinDeskClient $coinDeskClient,
        MoneyFormatter $moneyFormatter
    ) {
        $rateValue = $cache->get('app.bitcoin.value', static function (ItemInterface $item) use ($coinDeskClient) {
            // Don't fetch value everytime, store it with a minute of cache
            $item->expiresAfter(60);

            $rateValue = $coinDeskClient->getBitcoinRateValue();

            return $rateValue ? (int) ($rateValue * 100) : null;
        });

        return $this->render('_partials/bitcoin_rate_value.html.twig', [
            'rateValue' => $rateValue ? new Money($rateValue, new Currency('USD')) : null
        ]);
    }
}
