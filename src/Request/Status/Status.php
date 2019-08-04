<?php

namespace walangkaji\ZteF609\Request\Status;

use walangkaji\ZteF609\ZteApi;
use walangkaji\ZteF609\Constants;
use walangkaji\ZteF609\GlobalFunction as Func;

/**
 * Status Class
 */
class Status extends ZteApi
{

    function __construct($parent)
    {
        $this->zte   = $parent;
        $this->NetworkInterface = new NetworkInterface($this);
        $this->UserInterface    = new UserInterface($this);
    }

    /**
     * Get device information
     * 
     * @return object
     */
    public function deviceInformation()
    {
        $request = $this->zte->request($this->zte->modemUrl . Constants::DEVICE_INFORMATION);
        $dom     = str_get_html($request);

        $data = [];
        foreach($dom->find('table#TABLE_DEV tr') as $key) {
            $cari        = $key->find('td');
            $keys        = strtolower(str_replace(' ', '_', $cari[0]->plaintext));
            $data[$keys] = ltrim(rtrim(html_entity_decode($cari[1]->plaintext)));

            if ($keys == 'pon_serial_number') {
                $data[$keys] = Func::find($dom, 'var sn = "', '";');
            }
        }

        return json_decode(json_encode($data));
    }

    /**
     * Get VoIP Status
     * @return object
     */
    public function voIpStatus()
    {
        $request = $this->zte->request($this->zte->modemUrl . Constants::VOIP_STATUS);
        $dom     = str_get_html($request);

        foreach ($dom->find('table#TestContent td[class=tdright]') as $key) {
            $status[] = $key->innertext;
        }

        $data = [
            'phone1'           => $status[0],
            'register_status1' => $status[1],
            'phone2'           => $status[2],
            'register_status2' => $status[3],
        ];

        return json_decode(json_encode($data));
    }

}
