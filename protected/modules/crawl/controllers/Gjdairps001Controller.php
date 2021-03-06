<?php
/**
 * 测试
 *
 * @auth degang.shen
 */
class Gjdairps001Controller extends Controller
{
    /**
     * @var string way url
     */
    private $flight_one_url = 'http://sale.transaero.ru/step2?departureAirportCode=%s&arrivalAirportCode=%s&adultsNum=1&childsNum=0&infantsNum=0&cabinName=touristic&departureDate=%s&returnDate=&way=one-way&send=Search&departureAirport=LON';

    /**
     * get flight info
     * 
     * @return mixed $flights
     */
    private function getFlightInfo()
    { //{{{
        $this->cur_url = 'http://www.transaero.ru/en/';
        $curl = $this->curl();
        $html_info = $curl->get($this->flight_one_url);  
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
     * 网页解析,获取机票信息 
     */
    public function actionProcess()
    { //{{{
        $this->flight_one_url = sprintf($this->flight_one_url, strtoupper($this->request['dep']), 
            strtoupper($this->request['arr']), $this->request['depDate']);
        $flights = $this->getFlightInfo();
        if ($flights === false)
        {
            $this->flight_res['ret'] = false;
            $this->flight_res['status'] = 'CONNECTION_FAIL';
        }

        if ($flights['success'] == 1)
        {
            $flights_data = $flights['data']['journeys']; 
            $is_transit = $flights['data']['isTransit'];

            if ($is_transit)
            {
                //请求economy类型
                $this->cur_url = 'http://sale.transaero.ru/api/get-flight-prices-by-class?cabinName=economy';
                $curl = $this->curl();
                $economy_info = $curl->get($this->cur_url);
                $economy_info = CJSON::decode($economy_info);
            }
            else
            {
                $this->cur_url = 'http://sale.transaero.ru/api/get-flight-prices-by-class?cabinName=touristic'; 
                $curl = $this->curl();
                $touristic_list = $curl->get($this->cur_url);
                $touristic_list = CJSON::decode($touristic_list);
                
                $this->cur_url = 'http://sale.transaero.ru/api/get-flight-prices-by-class?cabinName=economy_full';
                $curl = $this->curl();
                $economy_full_list = $curl->get($this->cur_url);
                $economy_full_list = CJSON::decode($economy_full_list);
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

                        $total_price = '';
                        $tax_price = '';
                        //判断是否是标准服务
                        if ($is_transit)
                        {
                            //请求价格 
                            $this->cur_url = 'http://sale.transaero.ru/api/get-price-result-table?rph='.$i.'&cabinName=economy&directResponse=1';
                            $curl = $this->curl();
                            $economy_list = $curl->get($this->cur_url);
                            $price_list = CJSON::decode($economy_list);
                        }
                        else
                        {
                            $this->cur_url = 'http://sale.transaero.ru/api/get-price-result-table?rph='.$i.'&cabinName=touristic&directResponse=1';
                            $curl = $this->curl();
                            $touristic_info = $curl->get($this->cur_url);
                            $price_list = CJSON::decode($touristic_info);
                            if (!$price_list)
                            {
                                $this->cur_url = 'http://sale.transaero.ru/api/get-price-result-table?rph='.$i.'&cabinName=economy_full&directResponse=1';
                                $curl = $this->curl();
                                $economy_full_info = $curl->get($this->cur_url);
                                $price_list = CJSON::decode($economy_full_info);
                            }
                        }

                        $total_price = isset($price_list[0]['rate']) ? $price_list[0]['rate'] : '';
                        $tax_price = isset($price_list[0]['tax']) ? $price_list[0]['tax'] : '';

                        $data['data'][$key]['detail']['monetaryunit'] = 'RUB';
                        $data['data'][$key]['detail']['price'] = $total_price;
                        $data['data'][$key]['detail']['status'] = 0;
                        $data['data'][$key]['detail']['tax'] = (float)$tax_price;
                        $data['data'][$key]['detail']['wrapperid'] = $this->request['wrapperid'];

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
                        $data['data'][$key]['info'][] = $flight_info;
                    }
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
     * one Way booking
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
        $this->flight_one_url = sprintf($this->flight_one_url, strtoupper($this->request['dep']), 
            strtoupper($this->request['arr']), $this->request['depDate']);
        $flight_url = $this->flight_one_url;

        parse_str(substr($flight_url, strpos($flight_url, '?') + 0x01, strlen($flight_url)) ,$flight_one_arr);

        $booking = array('action' => substr($flight_url, 0, strpos($flight_url, '?')), 'contentType' => 'utf-8',
            'method' => 'get', 'inputs' => $flight_one_arr); 
        return $booking;
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
