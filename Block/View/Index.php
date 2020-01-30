<?php
namespace PhilTurner\LogViewer\Block\View;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;
use PhilTurner\LogViewer\Helper\Data;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Data
     */
    protected $logDataHelper;
    protected $scopeConfig;
    const XML_PATH_LINES = 'dev/logviewer/lines';


    /**
     * Index constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Context $context
     * @param Data $logDataHelper
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Context $context,
        Data $logDataHelper,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->logDataHelper = $logDataHelper;
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getLogFile()
    {
        $storeScope = ScopeInterface::SCOPE_STORE;
        $lines = $this->scopeConfig->getValue(self::XML_PATH_LINES, $storeScope);
        if ($lines == 0) {
            $lines = 50;
        }
        $params = $this->_request->getParams();
        $tail =  $this->logDataHelper->getLastLinesOfFile($params[0], $lines);
        $html =  str_replace(['\r\n', '\r', '\n'], "<br>", htmlspecialchars($tail));
        return $html;

    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        $params = $this->_request->getParams();
        return $params[0];
    }
}
