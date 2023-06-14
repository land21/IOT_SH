
//ini hanya sebagai library
#include <WiFi.h>
#include <PubSubClient.h>
#include "DHT.h"

//ini untuk wifi
const char* ssid = "Wokwi-GUEST";
const char* password = "";
//ini untuk server broker 
const char* mqtt_server = "sh001.cloud.shiftr.io";

//menyesuaikan dengan pin
#define DHTPIN 15
#define DHTTYPE DHT22 
#define LED_PIN 5
DHT dht(DHTPIN, DHTTYPE);

//ini untuk client client 
WiFiClient espClient;
PubSubClient client(espClient);
//unsigned long lebih banyak menyimpan data dibanding dengan long biasa
unsigned long lastMsg = 0;
#define MSG_BUFFER_SIZE  (50) // panjang longnya maksimal 50
char msg[MSG_BUFFER_SIZE]; //karakter message panjangnya 50 
int value = 0; //hanya initialisasi

void setup_wifi() {
  delay(10);
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

//selama wifinya blm terkoneksi dia akan nge print titik titik terus
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  randomSeed(micros());

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}

// Fungsi untuk menerima data
//topic dia akan mengambil di client subscribe 
// dari topic akan ada payloadnya
void callback(char* topic, byte* payload, unsigned int length) {
  Serial.print("Pesan diterima [");
  Serial.print(topic);
  Serial.print("] ");
  //jika karakter dlm if else akan menggunakan tanda kutip
  if (payload[0] == '1'){
    digitalWrite(LED_PIN, HIGH);
  }else{
    digitalWrite(LED_PIN, LOW);
  }
}


// fungsi untuk menghubungkan ke broker
//dipanggil di bagian loop
void reconnect() {
  //selama client blm terkoneksi maka dia akan terus mengulang
  while (!client.connect("Kitchen","sh001","F9gh2Bdlj0OPOWoZ")) {
    Serial.print(".");
    delay(1000); 
  }
  Serial.println("Terhubung");
  //subscribenya cuma led karena yg diterima hanya data led, dia mengirimkan data suhu dan kelembapan
  client.subscribe("iot/SH/kitchen/led"); // topik yang bakal diambli
}

void setup() {
  Serial.begin(115200); //serial monitor
  setup_wifi(); // manggil setup wifi yg diatas
  client.setServer(mqtt_server, 1883); //nanti brokernya akan di set menyesuaikan dengan mqtt yg diatas
  dht.begin(); //melihat data dht
  pinMode(LED_PIN, OUTPUT);
  client.setCallback(callback); //ngambil data callback
}

void loop() {
  delay(2000);
  //jika client blm terkoneksi maka dia akan memanggil rekonek
  if (!client.connected()) {
    reconnect();
  }
  //dia akan terus ngambil data dan ngirim data
  client.loop();
  //t= temperature,h humadity atau kelembapan
  float t = dht.readTemperature();
  float h = dht.readHumidity();
  if (isnan(h) || isnan(t)) {
    Serial.println("Failed to read from DHT sensor!");
  }
  snprintf (msg, MSG_BUFFER_SIZE, "%s", itoa(t,msg,20));
  Serial.print("Suhu : ");
  Serial.println(msg);
  client.publish("iot/SH/kitchen/Suhu", msg); 
  snprintf (msg, MSG_BUFFER_SIZE, "%s", itoa(h,msg,20));
  Serial.print("Kelembapan : ");
  Serial.println(msg);
  client.publish("iot/SH/kitchen/Kelembapan", msg); 
}