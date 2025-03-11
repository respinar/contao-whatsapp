<?php

declare(strict_types=1);

$GLOBALS["TL_DCA"]["tl_module"]["palettes"]["whatsapp"] = '
    {title_legend},name,headline,type;
    {whatsapp_legend},whatsappTitle,whatsappNumber,whatsappMessage;
    {template_legend:hide},cssID,customTpl;
    {protected_legend:hide},protected;
';

$GLOBALS["TL_DCA"]["tl_module"]["fields"]["whatsappTitle"] = [
    "inputType" => "text",
    "eval" => ["mandatory" => true, "maxlength" => 255, "tl_class" => "w50"],
    "sql" => "varchar(255) NOT NULL default ''",
];

$GLOBALS["TL_DCA"]["tl_module"]["fields"]["whatsappNumber"] = [
    "inputType" => "text",
    "eval" => ["mandatory" => true, "maxlength" => 20, "tl_class" => "w50 clr"],
    "sql" => "varchar(20) NOT NULL default ''",
];

$GLOBALS["TL_DCA"]["tl_module"]["fields"]["whatsappMessage"] = [
    "inputType" => "text",
    "eval" => ["mandatory" => true, "maxlength" => 255, "tl_class" => "w50"],
    "sql" => "varchar(255) NOT NULL default ''",
];
