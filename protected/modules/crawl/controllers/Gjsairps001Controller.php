<?php
/**
 * 测试
 *
 * @auth degang.shen
 */
class Gjsairps001Controller extends Controller
{
    /**
     * @var string round url
     */
    private $flight_round_url = 'http://sale.transaero.ru/step2?departureAirportCode=%s&arrivalAirportCode=%s&adultsNum=1&childsNum=0&infantsNum=0&cabinName=touristic&departureDate=%s&returnDate=%s&way=two-way&send=Search&departureAirport=LON';

    /**
     * get flight info
     * 
     * @param integer $type 0:way 1:round
     * @return mixed $flights
     */
    private function getFlightInfo()
    { //{{{
        $this->cur_url = 'http://www.transaero.ru/en/';
        $curl = $this->curl();
        $html_info = $curl->get($this->flight_round_url);  
        $header = $curl->getHeaders();
        
        $this->cur_url = 'http://sale.transaero.ru/api/get-prices-by-days';
        $curl = $this->curl();
        $days = $curl->get($this->cur_url);
        $days = CJSON::decode($days);

        $this->cur_url = 'http://sale.transaero.ru/api/get-available-flights';
        $curl = $this->curl();
        $flights = $curl->get($this->cur_url);
        $flights = CJSON::decode($flights);
   
        return $flights;
    } //}}}

    /**
     * round booking
     */
    public function actionBookingInfo()
    { //{{{
        $booking = $this->urlBooking();
        exit(CJSON::encode($booking));
    } //}}}

    /**
     * booking url 
     *
     * @return string $booking
     */
    private function urlBooking()
    { //{{{
        $this->flight_round_url = sprintf($this->flight_round_url, strtoupper($this->request['dep']), 
            strtoupper($this->request['arr']), $this->request['depDate'], $this->request['retDate']);
        $flight_url = $this->flight_round_url;

        parse_str(substr($flight_url, strpos($flight_url, '?') + 0x01, strlen($flight_url)) ,$flight_one_arr);

        $booking = array('action' => substr($flight_url, 0, strpos($flight_url, '?')), 'contentType' => 'utf-8',
            'method' => 'get', 'inputs' => $flight_one_arr); 
        return $booking;
    } //}}}

    /**
     * 网页解析,获取机票信息 
     */
    public function actionProcess()
    { //{{{
        $this->flight_round_url = sprintf($this->flight_round_url, strtoupper($this->request['dep']), 
            strtoupper($this->request['arr']), $this->request['depDate'], $this->request['retDate']);
        $flights = $this->getFlightInfo();
        if ($flights === false)
        {
            $this->flight_res['ret'] = false;
            $this->flight_res['status'] = 'CONNECTION_FAIL';
        }

        if ($flights['success'] == 1)
        {
            $flights_data = $flights['data']['journeys']; 
            $flights_ret_data = $flights['data']['returnJourneys']; 
            $flights_data = $this->merData($flights_data, $flights_ret_data);

            $is_transit = $flights['data']['isTransit'];

            if ($is_transit)
            {
                //请求economy类型
                $this->cur_url = 'http://sale.transaero.ru/api/get-flight-prices-by-class?cabinName=economy';
                $curl = $this->curl();
                $economy_info = $curl->get($this->cur_url);
                $economy_info = CJSON::decode($economy_info);
                $prices_rph = array();
                $economy_info = $this->flightPricesCabin($economy_info, 'economy');

                $this->cur_url = 'http://sale.transaero.ru/api/get-flight-prices-by-class?cabinName=business';
                $curl = $this->curl();
                $business_info = $curl->get($this->cur_url);
                $business_info = CJSON::decode($business_info);
                $business_info = $this->flightPricesCabin($business_info, 'business');

                $prices_rph = array_merge_recursive($economy_info, $business_info);
            }
            else
            {
                $this->cur_url = 'http://sale.transaero.ru/api/get-flight-prices-by-class?cabinName=touristic'; 
                $curl = $this->curl();
                $touristic_list = $curl->get($this->cur_url);
                $touristic_list = CJSON::decode($touristic_list);
                $prices_rph = array();
                $touristic_list = $this->flightPricesCabin($touristic_list, 'touristic');
                
                $this->cur_url = 'http://sale.transaero.ru/api/get-flight-prices-by-class?cabinName=economy_full';
                $curl = $this->curl();
                $economy_full_list = $curl->get($this->cur_url);
                $economy_full_list = CJSON::decode($economy_full_list);
                $economy_full_list = $this->flightPricesCabin($economy_full_list, 'economy_full');

                $prices_rph = array_merge_recursive($touristic_list, $economy_full_list);
               
            }
            $data = array();

            if (!empty($flights_data))
            {
                $i = 0;
                foreach ($flights_data as $key => $val) 
                {
                    $i += 1;
                    foreach($val['segments'] as $kseg => $segval)  
                    {
                        $data['data'][$key]['detail']['arrcity'] = $this->request['arr'];
                        $data['data'][$key]['detail']['depcity'] = $this->request['dep']; 
                        $data['data'][$key]['detail']['depdate'] = strtotime($this->request['depDate'].' 00:00:00').'000';
                        $data['data'][$key]['detail']['flightno'][] = $segval['marketingAirline'].$segval['flightNumber'];

                        $data['data'][$key]['detail']['wrapperid'] = $this->request['wrapperid'];
                        $data['data'][$key]['info'][] = $this->getFlightList($segval);
                    }

                    $rph_num = $val['rph'];
                    $way_rph = $prices_rph['journey_rph']['rph_'.$val['rph']];
                    $data['data'][$key]['outboundPrice'] = is_array($way_rph['baseFare']) ? (float)$way_rph['baseFare'][0] : (float)$way_rph['baseFare'];
                    $cabin_name = is_array($way_rph['type']) ? $way_rph['type'][0] : $way_rph['type'];

                    //return flight
                    $flight_ret = $val['ret_info']['segments'];
                    foreach ($flight_ret as $retkey => $retval)
                    {
                        $data['data'][$key]['retdepdate'] = strtotime($this->request['retDate'].' 00:00:00').'000';
                        $data['data'][$key]['retflightno'][] = $retval['marketingAirline'].$retval['flightNumber'];
                        $data['data'][$key]['retinfo'][] = $this->getFlightList($retval);
                    }
                    $rph_return_num = $val['ret_info']['rph'];
                    $ret_rph = $prices_rph['return_rph']['rph_'.$val['ret_info']['rph']];
                    $data['data'][$key]['returnedPrice'] = is_array($ret_rph['baseFare']) ? (float)$ret_rph['baseFare'][0] : (float)$ret_rph['baseFare'];
                    $return_cabin_name = is_array($ret_rph['type']) ? $ret_rph['type'][0] : $ret_rph['type'];

                    $total_price = '';
                    $tax_price = '';
                    //获取价格 
                    $this->cur_url = 'http://sale.transaero.ru/api/get-price-result-table?rph='.$rph_num.'&returnRPH='.$rph_return_num.
                        '&cabinName='.$cabin_name.'&returnCabinName='.$return_cabin_name.'&directResponse=1&returnResponse=1';
                    $curl = $this->curl();
                    $price_info = $curl->get($this->cur_url);
                    $price_info = CJSON::decode($price_info);

                    $total_price = isset($price_info[0]['rate']) ? $price_info[0]['rate'] : '';
                    $tax_price = isset($price_info[0]['tax']) ? $price_info[0]['tax'] : '';

                    $data['data'][$key]['detail']['monetaryunit'] = 'RUB';
                    $data['data'][$key]['detail']['price'] = $total_price;
                    $data['data'][$key]['detail']['status'] = 0;
                    $data['data'][$key]['detail']['tax'] = (float)$tax_price;


                    $this->flight_res['ret'] = true;
                    $this->flight_res['status'] = 'SUCCESS';
                }
            }

        }
        else
        {
            $this->flight_res['ret'] = true;
            $this->flight_res['status'] = 'NO_RESULT';
        }

        $data['ret'] = $this->flight_res['ret'];
        $data['status'] = $this->flight_res['status'];
        isset($data['data']) && $data['data'] = array_values($data['data']);
        $this->toJson($data);
    } //}}}

    /**
     * 组合数据
     *
     * @param array $touristic_list
     * @param string $type
     * @return array $prices_rph
     */
    private function flightPricesCabin($touristic_list, $type)
    { //{{{
        $prices_rph = array();
        if (isset($touristic_list['separateJourneysCosts']))
        {
            foreach ($touristic_list['separateJourneysCosts'] as $toukey => $touval)
            {
                //判是去程还是返程
                if (isset($touval['returnJourneyRPH']))
                {
                    $prices_rph['return_rph']['rph_'.$touval['returnJourneyRPH']] = array(
                        'baseFare' => $touval['passengersCosts'][0]['baseFare'],
                        'type' => $type
                    ); 
                }
                if (isset($touval['journeyRPH']))
                {
                    $prices_rph['journey_rph']['rph_'.$touval['journeyRPH']] = array(
                        'baseFare' => $touval['passengersCosts'][0]['baseFare'],
                        'type' => $type
                    ); 
                }
            }
        }
        return $prices_rph;
    } //}}}

    /**
     * 合并数据
     *
     * @param array $way_data
     * @param array $ret_data
     * @return array $new_data
     */
    private function merData($way_data, $ret_data)
    { //{{{
        $new_data = array();
        $i = 0;
        foreach ($way_data as $key => $val) 
        {
            foreach ($ret_data as $rkey => $rval) 
            {
                $i += 1;
                $new_data[$i] = $val;
                $new_data[$i]['ret_info'] = $rval;
            }
        }

        return $new_data;
    } //}}}

    /**
     * 处理航班详情列表
     *
     * @param array $segval
     * @return array $flight_info
     */
    private function getFlightList($segval)
    { //{{{
        $departure_date_time_arr = explode('T', $segval['departureDateTime']);
        $arrival_date_time_arr = explode('T', $segval['arrivalDateTime']);
        $flight_info = array(
            'arrDate' => $arrival_date_time_arr[0],     
            'arrairport' => $segval['arrivalAirport'],
            'arrtime' => substr($arrival_date_time_arr[1], 0, 5),
            'depDate' => $departure_date_time_arr[0],
            'depairport' => $segval['departureAirport'],
            'deptime' => substr($departure_date_time_arr[1], 0, 5),
            'flightno' => $segval['marketingAirline'].$segval['flightNumber'],
        );

        return $flight_info;
    } //}}}
}
