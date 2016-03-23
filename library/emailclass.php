<? 
class CMailFile
{ 

var $subject; 
var $addr_to; 
var $text_body; 
var $text_encoded; 
var $mime_headers; 
var $mime_boundary = "--==================_846811060==_"; 
var $smtp_headers; 

function CMailFile($subject,$to,$from,$msg,$filename,$downfilename,$mimetype = "application/octet-stream",$mime_filename = false) { 
$this->subject = $subject; 
$this->addr_to = $to; 
$this->smtp_headers = $this->write_smtpheaders($from); 
$this->text_body = $this->write_body($msg); 
$this->text_encoded = $this->attach_file($filename,$downfilename,$mimetype,$mime_filename); 
$this->mime_headers = $this->write_mimeheaders($filename, $mime_filename); 
} 

function attach_file($filename,$downfilename,$mimetype,$mime_filename) { 
$encoded = $this->encode_file($filename); 
if ($mime_filename) $filename = $mime_filename; 
$out = "--" . $this->mime_boundary . "\n"; 
$out = $out . "Content-type: " . $mimetype . "; name=\"$filename\";\n"; 
$out = $out . "Content-Transfer-Encoding: base64\n"; 
$out = $out . "Content-disposition: attachment; filename=\"$downfilename\"\n\n"; 
$out = $out . $encoded . "\n"; 
$out = $out . "--" . $this->mime_boundary . "--" . "\n"; 
return $out; 
} 

function encode_file($sourcefile) { 
if (is_readable($sourcefile)) { 
$fd = fopen($sourcefile, "r"); 
$contents = fread($fd, filesize($sourcefile)); 
$encoded = chunk_split(base64_encode($contents)); 
fclose($fd); 
} 
return $encoded; 
} 

function sendfile() { 
$headers = $this->smtp_headers . $this->mime_headers; 
$message = $this->text_body . $this->text_encoded; 
mail($this->addr_to,$this->subject,$message,$headers); 
} 

function write_body($msgtext) { 
$out = "--" . $this->mime_boundary . "\n"; 
$out = $out . "Content-Type: text/plain; charset=\"us-ascii\"\n\n"; 
$out = $out . $msgtext . "\n"; 
return $out; 
} 

function write_mimeheaders($filename, $mime_filename) { 
if ($mime_filename) $filename = $mime_filename; 
$out = "MIME-version: 1.0\n"; 
$out = $out . "Content-type: multipart/mixed; "; 
$out = $out . "boundary=\"$this->mime_boundary\"\n"; 
$out = $out . "Content-transfer-encoding: 7BIT\n"; 
$out = $out . "X-attachments: $filename;\n\n"; 
return $out; 
} 

function write_smtpheaders($addr_from) { 
$out = "From: $addr_from\n"; 
$out = $out . "Reply-To: $addr_from\n"; 
$out = $out . "X-Mailer: PHP3\n"; 
$out = $out . "X-Sender: $addr_from\n"; 
return $out; 
} 
} 

/*用法 - 例如:mimetype 为 "image/gif" 
$mailfile = new CMailFile($subject,$sendto,$replyto,$message,$filename,$mimetype); 
$mailfile->sendfile(); 

$subject -- 主题 
$sendto -- 收信人地址 
$replyto -- 回复地址 
$message -- 信件内容 
$filename -- 附件文件名 
$downfilename -- 下載的文件名 
$mimetype -- mime类型 
*/ 
?> 