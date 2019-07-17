<?php

namespace Invest\Reports;

use Mpdf\Mpdf;

class Report {
    private $mpdf;

    //user related variables
    private $user;
    private $wallet;
    private $portfolio;
    private $sum_buy;

    public function __construct($user, $wallet, $portfolio, $sum_buy, $actions_buys) {

        $this->mpdf = new Mpdf();
        $this->user = strtoupper($user);
        $this->wallet = number_format($wallet, 2, ',', '.');
        $this->portfolio = $portfolio;
        $this->sum_buy = number_format($sum_buy, 2, ',', '.');
        $this->$actions_buys = number_format($actions_buys, 2, ',', '.');
    }



    public function get() {
        $this->mpdf->Image('assets/img/logo/mllr.png', 10, 10, 50, 25, 'png');
        $this->mpdf->setTitle("MLLR INVESTIMENTOS - RELATÓRIO $this->user - " . date("d-m-Y"));
        $this->mpdf->WriteHTML("<h3> TITULAR: $this->user</h3>");
        $this->mpdf->WriteHTML("<h3> VALOR EM CARTEIRA: R$$this->wallet</h3>");
        $this->mpdf->WriteHTML("<h3> SOMA DAS AÇÕES COMPRADAS: R$$this->sum_buy</h3>");
        $this->mpdf->WriteHTML("<br><br>");
        $this->mpdf->WriteHTML("<h2> PORTFÓLIO </h2><hr>");
        $this->mpdf->WriteHTML("<br>");

        foreach ($this->portfolio as $company) {
            $this->mpdf->WriteHTML($company['COMPANY_SYMBOL']);
        }

        $this->mpdf->WriteHTML("<h2> RESULTADO POR TEMPO </h2> <hr>");
        $this->mpdf->WriteHTML("<h4> VALOR DA AÇÕES COMPRADAS: </h4>");

        foreach ($this->actions_buys as $value) {
            $this->mpdf->WriteHTML("R$".$value['TRANSACTION_TOTAL']);
        }

        $this->mpdf->WriteHTML("<br>");
        $this->mpdf->WriteHTML("<h2> RESULTADO POR AÇÃO </h2> <hr>");

        return $this->mpdf->Output();
    }

}