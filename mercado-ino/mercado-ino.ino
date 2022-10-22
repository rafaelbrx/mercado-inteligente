//Bibliotecas utilizadas
#include <WiFi.h>
#include <HTTPClient.h>
#include <HTTPUpdate.h>
#include "ESPino32CAM.h"
#include "ESPino32CAM_QRCode.h"
 
ESPino32CAM cam;   //Objeto para captura de imagem
ESPino32QRCode qr; //Objeto para decoficação da imagem
 
//Define os pinos da câmera
#define PWDN_GPIO_NUM     32
#define RESET_GPIO_NUM    -1
#define XCLK_GPIO_NUM      0
#define SIOD_GPIO_NUM     26
#define SIOC_GPIO_NUM     27
#define Y9_GPIO_NUM       35
#define Y8_GPIO_NUM       34
#define Y7_GPIO_NUM       39
#define Y6_GPIO_NUM       36
#define Y5_GPIO_NUM       21
#define Y4_GPIO_NUM       19
#define Y3_GPIO_NUM       18
#define Y2_GPIO_NUM        5
#define VSYNC_GPIO_NUM    25
#define HREF_GPIO_NUM     23
#define PCLK_GPIO_NUM     22
#define flash 4
 

const char ssid[] = ""; //Nome WiFi
const char password[] = ""; //Senha WiFi
String HOST_NAME = "http://xxx.xxx.x.xxx/ESP32.php?codbarras=xxxxxx"; // Endereço IP do seu computador + QR Code

 
//Variável para limitar o print no monitor serial se caso o QR Code ainda estiver na frente da câmera
int trava;
int vermelho=14;
int verde=2;

 
void setup() {
   
  pinMode(vermelho,OUTPUT);
  pinMode(verde,OUTPUT);
  Serial.begin(115200);
  Serial.println("Leitor de QR Code");
  //Define pino do flash
  pinMode(flash,OUTPUT);
  digitalWrite(flash,LOW);//Desliga o flash
     
  //Configura os pinos da câmera
  camera_config_t config;
  config.ledc_channel = LEDC_CHANNEL_0;
  config.ledc_timer = LEDC_TIMER_0;
  config.pin_d0 = Y2_GPIO_NUM;
  config.pin_d1 = Y3_GPIO_NUM;
  config.pin_d2 = Y4_GPIO_NUM;
  config.pin_d3 = Y5_GPIO_NUM;
  config.pin_d4 = Y6_GPIO_NUM;
  config.pin_d5 = Y7_GPIO_NUM;
  config.pin_d6 = Y8_GPIO_NUM;
  config.pin_d7 = Y9_GPIO_NUM;
  config.pin_xclk = XCLK_GPIO_NUM;
  config.pin_pclk = PCLK_GPIO_NUM;
  config.pin_vsync = VSYNC_GPIO_NUM;
  config.pin_href = HREF_GPIO_NUM;
  config.pin_sscb_sda = SIOD_GPIO_NUM;
  config.pin_sscb_scl = SIOC_GPIO_NUM;
  config.pin_pwdn = PWDN_GPIO_NUM;
  config.pin_reset = RESET_GPIO_NUM;
  config.xclk_freq_hz = 20000000;
  config.pixel_format = PIXFORMAT_JPEG;
  config.frame_size = FRAMESIZE_VGA;  
  config.jpeg_quality = 4;
  config.fb_count = 1;
   
  esp_err_t err = esp_camera_init(&config); //Inicialização da câmera
   
  if (err != ESP_OK) {
     
    Serial.printf("O início da câmera falhou com erro 0x%x", err);//Informa erro se a câmera não for iniciada corretamente
    delay(1000);
    ESP.restart();//Reinicia o ESP
     
  }
 
  //Inicializa o objeto de decodificação
  qr.init(&cam);
  sensor_t *s = cam.sensor();
  s->set_framesize(s, FRAMESIZE_CIF);
  s->set_whitebal(s, true);
   
  Serial.println();
  Serial.println("Aguardando código"); 


  WiFi.begin(ssid, password);
  Serial.println("Connecting");
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
  delay(1000);
  
 
}


HTTPClient http;

void loop()
{
  unsigned long pv_time  = millis();
  camera_fb_t *fb = cam.capture(); //Captura a imagem
  if (!fb)
  {
    Serial.println("Falha na captura da imagem");
    return;
    digitalWrite(verde,LOW);
    digitalWrite(vermelho,HIGH);
    delay(3000);
    digitalWrite(vermelho,LOW);
    
  }
  dl_matrix3du_t *rgb888, *rgb565;
  if (cam.jpg2rgb(fb, &rgb888))
  {
    rgb565 = cam.rgb565(rgb888);
  }
  cam.clearMemory(rgb888);
  cam.clearMemory(rgb565);
  dl_matrix3du_t *image_rgb;
  if (cam.jpg2rgb(fb, &image_rgb))
  {
    cam.clearMemory(fb);
         
    qrResoult res = qr.recognition(image_rgb); //Faz decodificação da imagem contendo os dados
     
    if (res.status) //Se conseguir decodificar a imagem mostra os dados na tela
    { 
       
      if(trava == 0){
       digitalWrite(vermelho,LOW);
       digitalWrite(verde,HIGH);
       delay(3000);
       digitalWrite(verde,LOW);   
       trava = 1;
       
    //res.payload é a leitura do código de barras para adicionar a lista de compras, porém por conta de um erro de segurança do esp não funciona
   // para resolver isso adicione um dos códigos qr no HOST_NAME e qualquer qr code lido será adicionado o produto que acompanha o HOST_NAME
  //próximas versões será resolvido
        String server = HOST_NAME;
        Serial.println(server);
        
         http.begin(server); 

         }
        int httpCode = http.GET();
      
        if(httpCode > 0) {
          if(httpCode == HTTP_CODE_OK) {
            String payload = http.getString();
            Serial.println(payload);
          } else {
            Serial.printf("HTTP GET... code: %d\n", httpCode);
          }
        } else {
          Serial.printf("HTTP GET... failed, error: %s\n", http.errorToString(httpCode).c_str());
        }
      
        http.end();
        
       } else{ //Se não aguarda receber código 
        
       trava = 0;
       Serial.println();
       Serial.println("Aguardando código"); 
       digitalWrite(verde,LOW);
       digitalWrite(vermelho,HIGH);
       delay(300);
       digitalWrite(vermelho,LOW);
       delay(300);
     
    }
    }
  
     
  cam.clearMemory(image_rgb); //Apaga imagem para receber uma nova imagem
}
