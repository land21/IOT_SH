#include <WiFi.h>
#include <PubSubClient.h>
#include "DHT.h"

// Update these with values suitable for your network.

const char* ssid = "Wokwi-GUEST";
const char* password = "";
const char* mqtt_server = "sh001.cloud.shiftr.io";

#define DHTPIN 2 
#define LED_PIN 14
#define DHTTYPE    DHT22 
DHT dht(DHTPIN, DHTTYPE);

WiFiClient espClient;
PubSubClient client(espClient);
unsigned long lastMsg = 0;
#define MSG_BUFFER_SIZE  (50)
char msg[MSG_BUFFER_SIZE];
int value = 0;

void callback(char* topic, byte* payload, unsigned int length) {
  
  if (payload[0] == '1'){
    Serial.print("[");
    Serial.print("Nyalakan LED");
    Serial.println("] ");
    digitalWrite(LED_PIN, HIGH);
  }else{
    Serial.print("[");
    Serial.print("Matikan LED");
    Serial.println("] ");
    digitalWrite(LED_PIN, LOW);
  }
}

void setup_wifi() {
  delay(10);
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);

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


// fungsi untuk menghubungkan ke broker
void reconnect() {
  while (!client.connect("Bedroom","sh001","F9gh2Bdlj0OPOWoZ")) {
    Serial.print(".");
    delay(1000);
    // Serial.print("Mencoba untuk menghubungkan dengan MQTT\n");
    // String clientId = "ESP8266Client- ";
    // clientId += String(random(0xffff), HEX);
    // if (client.connect(clientId.c_str())) {
    //   Serial.println("Terhubung");
    //   client.subscribe("iot/SH/bedroom/led");
    // } else {
    //   Serial.print("Gagal Terhubung, rc=");
    //   Serial.print(client.state());
    //   Serial.println("Mencoba dalam 5 detik");
    //   // Wait 5 seconds before retrying
    //   delay(5000);
    // }
  }
  Serial.println("Terhubung");
  client.subscribe("iot/SH/bedroom/led");
}

void setup() {
  Serial.begin(115200);
  pinMode(LED_PIN, OUTPUT);
  setup_wifi();
  client.setServer(mqtt_server, 1883);
  dht.begin();
  client.setCallback(callback);
}

void loop() {
  delay(2000);
  if (!client.connected()) {
    reconnect();
  }
  client.loop();
  float t = dht.readTemperature();
  float h = dht.readHumidity();
  if (isnan(h) || isnan(t)) {
    Serial.println("Failed to read from DHT sensor!");
  }
  snprintf (msg, MSG_BUFFER_SIZE, "%s", itoa(t,msg,20));
  Serial.print("Suhu : ");
  Serial.println(msg);
  client.publish("iot/SH/bedroom/Suhu", msg); 
  snprintf (msg, MSG_BUFFER_SIZE, "%s", itoa(h,msg,20));
  Serial.print("Kelembapan : ");
  Serial.println(msg);
  client.publish("iot/SH/bedroom/Kelembapan", msg); 
}