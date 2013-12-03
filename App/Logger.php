<?php

/**
 * アプリケーションログを出力するためのクラスです。
 *
 * @author  $Author: yasuhide.sanada $
 * @version $Id: Logger.php 185155 2013-10-01 08:08:21Z yasuhide.sanada $
 */
class App_Logger extends BEAR_Base
{
    /**
     * ログ出力する実装クラス
     *
     * @var App_Logger_BaseLogger
     */
    private $logger;

    /**
     * コンストラクタ　
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    /**
     * Loggerを設定、現状はApp_Logger_FluentLoggerのみ
     */
    public function onInject()
    {
        $this->logger = App_Logger_FluentLogger::open();
    }

    /**
     * レベルErrorでログを出力します。
     *
     * @param array $values
     */
    public function error(array $values)
    {
        $this->message('error', $values);
    }

    /**
     * レベルWarnでログを出力します。
     *
     * @param array $values
     */
    public function warn(array $values)
    {
        $this->message('warn', $values);
    }

    /**
     * レベルInfoでログを出力します。
     *
     * @param array $values
     */
    public function info(array $values)
    {
        $this->message('info', $values);
    }

    /**
     * レベルDebugでログを出力します。
     *
     * @param array $values
     */
    public function debug(array $values)
    {
        $this->message('debug', $values);
    }

    /**
     * 付加情報をつけてログを出力します。
     *
     * @param       $level
     * @param array $values
     * @param       $keyPrefix
     * @return boolean
     */
    protected function message($level, array $values, $keyPrefix = 'excite.app.')
    {
        $buffer = array();
        $buffer['_ENV_'] = json_encode($_SERVER);
        $buffer['_LOG_LEVEL_'] = $level;
        $buffer['uid'] = $_COOKIE['UID'];
        $buffer['values'] = json_encode($values);

        $service = 'unknown';
        if (isset($this->_config['service'])) {
            $service = $this->_config['service'];
        }

        if (isset($this->_config['keyprefix'])) {
            $keyPrefix = $this->_config['keyprefix'];
        }

        $buffer['service'] = $service;
        mb_convert_variables('UTF-8', null, $buffer);
        $result = $this->logger->post($keyPrefix . $service, $buffer);

        return $result;
    }
}
