<?php

namespace PHPCensor\Helper;

/**
 * Class MailerFactory helps to set up and configure a SwiftMailer object.
 */
class MailerFactory
{
    /**
     * @var array
     */
    protected $emailConfig;

    /**
     * Set the mailer factory configuration.
     * @param array $config
     */
    public function __construct($config = [])
    {
        if (!is_array($config)) {
            $config = [];
        }

        $this->emailConfig  = isset($config['email_settings']) ? $config['email_settings'] : [];
    }

    /**
     * Returns an instance of Swift_Mailer based on the config.s
     * @return \Swift_Mailer
     */
    public function getSwiftMailerFromConfig()
    {
        if ($this->getMailConfig('smtp_address')) {
            $encryptionType = $this->getMailConfig('smtp_encryption');

            // Workaround issue where smtp_encryption could == 1 in the past by
            // checking it is a valid transport
            if ($encryptionType && !in_array($encryptionType, stream_get_transports())) {
                $encryptionType = null;
            }

            /** @var \Swift_SmtpTransport $transport */
            $transport = new \Swift_SmtpTransport(
                $this->getMailConfig('smtp_address'),
                $this->getMailConfig('smtp_port'),
                $encryptionType
            );

            $transport->setUsername($this->getMailConfig('smtp_username'));
            $transport->setPassword($this->getMailConfig('smtp_password'));
        } else {
            $transport = new \Swift_SendmailTransport();
        }

        return new \Swift_Mailer($transport);
    }

    /**
     * Return a specific configuration value by key.
     * @param $configName
     * @return null|string
     */
    public function getMailConfig($configName)
    {
        if (isset($this->emailConfig[$configName]) && $this->emailConfig[$configName] != "") {
            return $this->emailConfig[$configName];
        } else {
            // Check defaults

            switch ($configName) {
                case 'smtp_address':
                    return "";
                case 'default_mailto_address':
                    return null;
                case 'smtp_port':
                    return '25';
                case 'smtp_encryption':
                    return null;
                default:
                    return "";
            }
        }
    }
}
