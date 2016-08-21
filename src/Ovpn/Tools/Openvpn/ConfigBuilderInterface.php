<?php

namespace Ovpn\Tools\Openvpn;

interface ConfigBuilderInterface
{
    /**
     * SSL/TLS parms. The root certificate (ca)
     *
     * @param string $ca
     * @param string $mode
     * @return ConfigBuilderInterface
     */
    public function addCa($ca, $mode = 'full');

    /**
     * SSL/TLS parms. The private key
     *
     * @param string $key
     * @param string $mode
     * @return ConfigBuilderInterface
     */
    public function addKey($key, $mode = 'full');

    /**
     * SSL/TLS parms. The client certificate
     *
     * @param string $cert
     * @param string $mode
     * @return ConfigBuilderInterface
     */
    public function addCert($cert, $mode = 'full');

    /**
     * This configuration for client connection
     *
     * @return ConfigBuilderInterface
     */
    public function addClientMode();

    /**
     * Use the same setting as you are using on the server.
     * On most systems, the VPN will not function unless you partially
     * or fully disable the firewall for the TUN/TAP interface.
     *
     * @param string $option. May be tun, tab
     * @return ConfigBuilderInterface
     */
    public function addDev($option = 'tun');

    /**
     * Are we connecting to a TCP or UDP server?
     *
     * @param string $option
     * @return ConfigBuilderInterface
     */
    public function addProto($option = 'tcp');

    /**
     * Do not bind to local address and port. The IP stack
     * will allocate a dynamic port for returning packets. Since the value
     * of the dynamic port could not be known in advance by a peer,
     * this option is only suitable for peers which will be initiating
     * connections by using the --remote option.
     *
     * @return ConfigBuilderInterface
     */
    public function addNobind();

    /**
     * Try to preserve some state across restarts.
     *
     * @return ConfigBuilderInterface
     */
    public function addPersistKey();

    /**
     * Try to preserve some state across restarts.
     *
     * @return ConfigBuilderInterface
     */
    public function addPersistTun();

    /**
     * Require that peer certificate was signed with an explicit
     * key usage and extended key usage based on RFC3280 TLS rules.
     * 
     * @param string $option
     * @return ConfigBuilderInterface
     */
    public function addRequireRemoteCertSign($option = 'server');

    /**
     * The hostname/IP and port of the server.
     *
     * @param $host
     * @param string $port
     * @return ConfigBuilderInterface
     */
    public function addRemote($host, $port = '1194');

    /**
     * Set the TCP/UDP socket send buffer size.
     * Defaults to operation system default.
     *
     * @param string $option
     * @return ConfigBuilderInterface
     */
    public function addSndbuf($option = '0');

    /**
     * Set the TCP/UDP socket receive buffer size.
     * Defaults to operation system default.
     *
     * @param string $option
     * @return ConfigBuilderInterface
     */
    public function addRcvbuf($option = '0');

    /**
     * Enable compression on the VPN link. Don't enable this
     * unless it is also enabled in the server config file.
     *
     * @return ConfigBuilderInterface
     */
    public function addComplzo();

    /**
     * Set log file verbosity.
     *
     * @param string $option
     * @return ConfigBuilderInterface
     */
    public function addVerb($option = '3');

    /**
     * Keep trying indefinitely to resolve the
     * host name of the OpenVPN server.
     *
     * @param string $option
     * @return ConfigBuilderInterface
     */
    public function addResolvRetry($option = 'infinite');

    /**
     * @return string
     */
    public function build();
}