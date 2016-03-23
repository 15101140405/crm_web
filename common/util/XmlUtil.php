<?php


class XmlUtil
{
    /**
     */
    public static function dom2array($node, $d = 1)
    {
        $result = array();
        if ($node->nodeType == XML_TEXT_NODE || $node->nodeType == XML_COMMENT_NODE || $node->nodeType == XML_CDATA_SECTION_NODE) {
            $result = $node->nodeValue;
            return $result;
        }

        if ($node->hasAttributes()) {
            $attributes = $node->attributes;
            if (!is_null($attributes))
                foreach ($attributes as $index => $attr)
                    $result[$attr->name] = $attr->value;
        }
        if ($node->hasChildNodes()) {
            $children = $node->childNodes;
            $hasMany = array();
            for ($i = 0; $i < $children->length; $i++) {
                $child = $children->item($i);
                if (!isset($result[$child->nodeName])) {
                    $result[$child->nodeName] = self::dom2array($child, $d + 1);
                } else {
                    if (!isset($hasMany[$child->nodeName])) {
                        $hasMany[$child->nodeName] = true;
                        $aux = $result[$child->nodeName];
                        $result[$child->nodeName] = array($aux);
                    }
                    $result[$child->nodeName][] = self::dom2array($child, $d + 1);
                }
            }
            if (count($result) == 1) {
                if (isset($result['#text'])) {
                    $result = $result['#text'];
                }
            }
        }
        return $result;
    }

    public function xmlfile2array($file)
    {
        $xml = file_get_contents($file);
        $dom = new DomDocument();
        try {
            // 注意把CDATA转换成TEXT
            $ret = @$dom->loadXml($xml, LIBXML_NOCDATA | LIBXML_NONET | LIBXML_NOBLANKS);
            if ($ret == false) {
                throw new Exception('解析xml错误');
            }
            $data = self::dom2array($dom->documentElement);
            //var_dump($this->data);
        } catch (Exception $e) {
            //Yii::log($e, 'error');
            return false;
        }
        return $data;
    }
}
