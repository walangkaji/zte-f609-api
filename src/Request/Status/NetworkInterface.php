<?php

namespace walangkaji\ZteF609\Request\Status;

use walangkaji\ZteF609\Constants;
use walangkaji\ZteF609\GlobalFunction as Func;

/**
 * NetworkInterface Class
 */
class NetworkInterface extends Status
{

    function __construct($parent)
    {
        $this->zte = $parent->zte;
    }

    /**
     * Get WAN Connection information
     * 
     * @return object
     */
    public function wanConnection()
    {
        $request = $this->zte->request($this->zte->modemUrl . Constants::WAN_CONNECTION);
        $dom     = str_get_html($request);

        $data = [];

        foreach($dom->find('table#TestContent0 tr') as $key) {
            $cari  = $key->find('td');
            $keys  = strtolower(str_replace(' ', '_', $cari[0]->plaintext));


            if ($keys == 'disconnect_reason') {
                $value = $cari[1]->plaintext;
            }else{
                $value = html_entity_decode($key->find('td.tdright input', 0)->attr['value']);
            }

            $data[$keys] = $value;
        }

        return json_decode(json_encode($data));
    }

    /**
     * 3G/4G WAN Connection
     */
    public function wanConnection3Gor4G()
    {
        return json_decode(json_encode(['error' => 'Under Maintenance']));
    }

    /**
     * 4in6 Tunnel Connection
     */
    public function tunnelConnection4in6()
    {
        return json_decode(json_encode(['error' => 'Under Maintenance']));
    }

    /**
     * 6in4 Tunnel Connection
     */
    public function tunnelConnection6in4()
    {
        return json_decode(json_encode(['error' => 'Under Maintenance']));
    }

    /**
     * PON information
     */
    public function ponInformation()
    {
        $request   = $this->zte->request($this->zte->modemUrl . Constants::PON_INFORMATION);
        $dom       = str_get_html($request);
        $regStatus = intval(Func::find($request, 'var RegStatus = "', '"'));

        switch ($regStatus) {
            case 1:
                $GponRegStatus = 'Initial State(o1)';
                break;
            case 2:
                $GponRegStatus = 'Standby State(o2)';
                break;
            case 3:
                $GponRegStatus = 'Serial Number State(o3)';
                break;
            case 4:
                $GponRegStatus = 'Ranging State(o4)';
                break;
            case 5:
                $GponRegStatus = 'Operation State(o5)';
                break;
            case 6:
                $GponRegStatus = 'POPUP State(o6)';
                break;
            case 7:
                $GponRegStatus = 'Emergency Stop State(o7)';
                break;
            default:
                $GponRegStatus = 'Unknown State';
                break;
        }

        $data    = [];
        $rxPower = Func::find($request, 'RxPower = "', '"') / 10000;
        $txPower = Func::find($request, 'TxPower = "', '"') / 10000;

        $data['gpon_state']                          = $GponRegStatus;
        $data['optical_module_input_power']          = Func::toFixed($rxPower, 1) . ' dBm';
        $data['optical_module_output_power']         = Func::toFixed($txPower, 1) . ' dBm';
        $data['optical_module_supply_voltage']       = $dom->find('td[id=Frm_Volt]', 0)->plaintext . ' uV';
        $data['optical_transmitter_bias_current']    = $dom->find('td[id=Frm_Current]', 0)->plaintext . ' uA';
        $data['optical_temperature_of_optical_mode'] = $dom->find('td[id=Frm_Temp]', 0)->plaintext . ' C';

        return json_decode(json_encode($data));
    }

    /**
     * Mobile Network
     */
    public function mobileNetwork()
    {
        $request   = $this->zte->request($this->zte->modemUrl . Constants::MOBILE_NETWORK);
        $dom       = str_get_html($request);

        $data = [];
        $data['service_provider'] = null;
        $data['network_mode']     = null;
        $data['signal_strength']  = count($dom->find('div.divbox'));
        $data['imei']             = null;
        $data['dongle_type']      = null;

        return json_decode(json_encode($data));
    }
}
