<?php /** @noinspection SpellCheckingInspection */

/**
 * Handles payment actions for Krediidipank
 * Specification: http://www.krediidipank.ee/business/settlements/bank-link/tehniline_kirjeldus.pdf
 * Has full support for UTF-8
 *
 * @date 17.04.2012
 * @author Ivo Kund <ivo@opus.ee>
 */
namespace opus\payment\adapters;

use opus\payment\services\payment\Dataset;
use opus\payment\services\payment\Response;
use yii\helpers\ArrayHelper;

/**
 * Class Krediidipank
 *
 * @author Ivo Kund <ivo@opus.ee>
 * @package opus\payment\adapters
 */
class Krediidipank extends AbstractIPizza
{
    /**
     * @inheritdoc
     */
    public $name = 'Krediidipank';

    /**
     * @inheritdoc
     */
    public function canHandlePaymentResponse(Response $response)
    {
        /** @noinspection SpellCheckingInspection */
        $isKrediidipank = 'KREP' == $response->getParam('VK_SND_ID', false);
        return $isKrediidipank && parent::canHandlePaymentResponse($response);
    }

    /**
     * @inheritdoc
     */
    public function getParamMacOrderDefinition()
    {
        return ArrayHelper::merge(
            parent::getParamMacOrderDefinition(),
            array(
                // 'transaction not completed' response with error message
                '1002' => array(
                    'VK_SERVICE',
                    'VK_VERSION',
                    'VK_SND_ID',
                    'VK_STAMP',
                    'VK_AMOUNT',
                    'VK_CURR',
                    'VK_REF',
                    'VK_MSG'
                ),
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function getParamValueLength($value)
    {
        // TODO: check if this should be multibyte (witch $this->getCharset())
        return strlen($value);
    }


}