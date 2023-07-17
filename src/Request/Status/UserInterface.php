<?php

namespace walangkaji\ZteF609\Request\Status;

use walangkaji\ZteF609\Constants;
use walangkaji\ZteF609\GlobalFunction as Func;

/**
 * User Interface Class
 */
class UserInterface extends Status
{
    public function __construct($parent)
    {
        $this->zte = $parent->zte;
    }

    /**
     * Get WAN Connection information
     *
     * @return object
     */
    public function wlan()
    {
        $request = $this->zte->request($this->zte->modemUrl . Constants::STATUS_USERINTERFACE_WLAN);
        preg_match_all('/>Transfer_meaning\((.*?)\);/', $request, $match);
        $result = str_replace("'", '', $match[1]);

        foreach ($result as $key) {
            $pecah = explode(',', $key);
            $dat[$pecah[0]] = stripcslashes($pecah[1]);
        }

        $map = [];
        for ($i=0; $i <= 3 ; $i++) {
            $authType = $this->getAuthenticationType(
                $dat["BeaconType$i"],
                $dat["WEPAuthMode$i"],
                $dat["WPAAuthMode$i"],
                $dat["11iAuthMode$i"]
            );

            $encryptionType = $this->getEncryptionType(
                $dat["BeaconType$i"],
                $dat["WPAEncryptType$i"],
                $dat["11iEncryptType$i"]
            );

            $map["wlan_$i"] = [
                'enable'                   => boolval($dat["Enable$i"]),
                'ssid_name'                => $dat["ESSID$i"],
                'authentication_type'      => $authType,
                'encryption_type'          => $encryptionType,
                'mac_address'              => $dat["Bssid$i"],
                'packets_received'         => $dat["TotalPacketsReceived$i"],
                'packets_sent'             => $dat["TotalPacketsSent$i"],
                'bytes_received'           => $dat["TotalBytesReceived$i"],
                'bytes_sent'               => $dat["TotalBytesSent$i"],
                'errors_received'          => $dat["ErrorsReceived$i"],
                'errors_sent'              => $dat["ErrorsSent$i"],
                'discard_packets_received' => $dat["DiscardPacketsReceived$i"],
                'discard_packets_sent'     => $dat["DiscardPacketsSent$i"],
                'wlan_ssid'                => $dat["WLAN_SSID$i"],
                'radio_status'             => boolval($dat["RadioStatus$i"]),
                'wep_auth_mode'            => $dat["WEPAuthMode$i"],
                'beacon_type'              => $dat["BeaconType$i"],
                'wpa_encrypt_type'         => $dat["WPAEncryptType$i"],
                'wpa_auth_mode'            => $dat["WPAAuthMode$i"],
                '11i_auth_mode'            => $dat["11iAuthMode$i"],
                '11i_encrypt_type'         => $dat["11iEncryptType$i"],
                'wds_mode'                 => $dat["WdsMode$i"],
                'channel_in_used'          => $dat["ChannelInUsed$i"],
                'mac_address'              => $dat["Bssid$i"],
                'real_rf'                  => $dat["RealRF$i"],
            ];
        }

        return json_decode(json_encode($map));
    }

    /**
     * Get Ethernet information
     *
     * @return object
     */
    public function ethernet()
    {
        $request = $this->zte->request($this->zte->modemUrl . Constants::STATUS_USERINTERFACE_ETHERNET);
        $dom     = str_get_html($request);
        $data    = [];

        foreach ($dom->find('table#TestContent tr') as $key => $val) {
            $cari          = $val->find('td');
            $keys          = strtolower(str_replace([' ', '/'], '_', rtrim($cari[0]->plaintext)));
            $data[$keys][] = html_entity_decode($cari[1]->plaintext);
        }

        $map = [];
        for ($i=0; $i <= 3 ; $i++) {
            $received = explode('/', $data['packets_received_bytes_received'][$i]);
            $sent     = explode('/', $data['packets_sent_bytes_sent'][$i]);

            $packets_received = $received[0];
            $byte_received    = $received[1];
            $packets_sent     = $sent[0];
            $byte_sent        = $sent[1];

            $map["lan_$i"] = [
                'ethernet_port'    => $data['ethernet_port'][$i],
                'status'           => $data['status'][$i],
                'speed'            => $data['speed'][$i],
                'mode'             => $data['mode'][$i],
                'packets_received' => $packets_received,
                'packets_sent'     => $packets_sent,
                'byte_received'    => $byte_received,
                'byte_sent'        => $byte_sent,
            ];
        }

        return json_decode(json_encode($map));
    }

    /**
     * Get USB information
     *
     * @return object
     */
    public function usb()
    {
        $request = $this->zte->request($this->zte->modemUrl . Constants::STATUS_USERINTERFACE_USB);
        $dom     = str_get_html($request);
        $data    = [];

        foreach ($dom->find('table#TestContent tr') as $key => $val) {
            $cari        = $val->find('td');
            $keys        = strtolower(str_replace([' ', '/'], '_', rtrim($cari[0]->plaintext)));
            $data[$keys] = html_entity_decode($cari[1]->plaintext);
        }

        return json_decode(json_encode($data));
    }

    /**
     * Get Authentication Type
     *
     * @param  string $beaconType  emboh pikir keri
     * @param  string $WEPAuthMode emboh pikir keri
     * @param  string $WPAAuthMode emboh pikir keri
     * @param  string $AuthMode11i emboh pikir keri
     */
    private function getAuthenticationType($beaconType, $WEPAuthMode, $WPAAuthMode, $AuthMode11i)
    {
        if ($beaconType == "None" || ($beaconType == "Basic" && $WEPAuthMode == "None")) {
            return 'Open System';
        } elseif ($beaconType == "Basic" && $WEPAuthMode == "SharedAuthentication") {
            return 'Shared Key';
        } elseif ($beaconType == "WPA" && $WPAAuthMode == "PSKAuthentication") {
            return 'WPA-PSK';
        } elseif ($beaconType == "11i" && $AuthMode11i == "PSKAuthentication") {
            return 'WPA2-PSK';
        } elseif ($beaconType == "WPAand11i" && $WPAAuthMode == "PSKAuthentication" && $AuthMode11i == "PSKAuthentication") {
            return 'WPA/WPA2-PSK';
        } elseif ($beaconType == "WPA" && $WPAAuthMode == "EAPAuthentication") {
            return 'WPA-EAP';
        } elseif ($beaconType == "11i" && $AuthMode11i == "EAPAuthentication") {
            return 'WPA2-EAP';
        } elseif ($beaconType == "WPAand11i" && $WPAAuthMode == "EAPAuthentication" && $AuthMode11i == "EAPAuthentication") {
            return 'WPA/WPA2-EAP';
        }
    }

    /**
     * Get Encryption Type
     *
     * @param  string $beaconType     emboh ra dong
     * @param  string $WPAEncryptType emboh ra dong
     * @param  string $EncryptType11i emboh ra dong
     */
    private function getEncryptionType($beaconType, $WPAEncryptType, $EncryptType11i)
    {
        if ($beaconType == "None") {
            return 'None';
        } elseif ($beaconType == "Basic") {
            return 'WEP';
        } elseif (($beaconType == "WPA" && $WPAEncryptType == "TKIPEncryption") ||
            ($beaconType == "11i" && $EncryptType11i == "TKIPEncryption") ||
            ($beaconType == "WPAand11i" && $WPAEncryptType == "TKIPEncryption")) {
            return 'TKIP';
        } elseif (($beaconType == "WPA" && $WPAEncryptType == "AESEncryption") ||
            ($beaconType == "11i" && $EncryptType11i == "AESEncryption") ||
            ($beaconType == "WPAand11i" && $WPAEncryptType == "AESEncryption")) {
            return 'AES';
        } elseif (($beaconType == "WPA" && $WPAEncryptType == "TKIPandAESEncryption") ||
            ($beaconType == "11i" && $EncryptType11i == "TKIPandAESEncryption") ||
            ($beaconType == "WPAand11i" && $WPAEncryptType == "TKIPandAESEncryption")) {
            return 'TKIP+AES';
        }
    }
}
