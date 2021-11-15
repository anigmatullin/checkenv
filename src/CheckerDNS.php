<?php

class CheckerDNS
{
    protected $required = [];

    protected $dnstypes = [
        "A" => DNS_A,
        "CNAME" => DNS_CNAME,
        "TXT" => DNS_TXT,
        "MX" => DNS_MX,
        "NS" => DNS_NS,
        "PTR" => DNS_PTR,
        "SOA" => DNS_SOA,
        "SRV" => DNS_SRV,
        ];

    protected $dnstypes_descr = [
        DNS_A => "A",
        DNS_CNAME => "CNAME",
        DNS_TXT => "TXT",
        DNS_MX => "MX",
        DNS_NS => "NS",
        DNS_PTR => "PTR",
        DNS_SOA => "SOA",
        DNS_SRV => "SRV",
    ];

    public function __construct()
    {
        $this->loadRequirements();
    }

    public function loadRequirements($path = "requirements/dns.txt")
    {
        $content = file_get_contents($path);
        $content = explode("\n", $content);

        foreach ($content as $line) {
            $line = trim($line);
            $row = preg_split("/\s/", $line);

            if (count($row) != 2) {
                continue;
            }

            $resource = $row[1];
            $type = $row[0];
            $dnstype = $this->dnstypes[$type];

            $this->required[] = [$resource, $dnstype];
        }
    }

    public function check()
    {
        $success = true;

        foreach ($this->required as $rec) {
            $resource = $rec[0];
            $dnstype = $rec[1];
            $typedescr = $this->dnstypes_descr[$dnstype];

            $res = dns_get_record($resource, $dnstype);

            if (!$res) {
                echo "Fail: DNS request failed: $resource $typedescr\n";
                $success = false;
            }
            else {
                echo "Success: DNS query for: $resource $typedescr\n";
            }

        }

        return $success;
    }
}
