#include <LiquidCrystal_I2C.h>
#include <WiFi.h>
#include <HTTPClient.h>
#include <WebServer.h>
#include <WifiClient.h>
#include <SPI.h>
#include <MFRC522.h>  //RFID library

#define SS_PIN 5  //RX slave select
#define RST_PIN 27
const char *nim;
int blockNum = 2;
MFRC522 mfrc522(SS_PIN, RST_PIN);
byte bufferLen = 18;
byte readBlockData[18];
MFRC522::StatusCode status;
MFRC522::MIFARE_Key key;
const char *ssid = "Wiro";
const char *password = "12345678";
const char *host = "180.ip.ply.gg";
byte blockData[16] = "";
String getData, Link1, Link2,Link3;
String CardID = "";
String NIM = "";
String payload1, payload2,payload3;
int httpCode;

LiquidCrystal_I2C lcd(0x27,20,4); 
void setup() {
  lcd.init();
  lcd.clear();         
  lcd.backlight();

  lcd.setCursor(0,0);   
  lcd.print("Sistem Absensi RFID");
  lcd.setCursor(2,1);   
  lcd.print("LAB INFORMATIKA");
  lcd.setCursor(7,2);   
  lcd.print("UMM");
  delay(3000);
  lcd.clear();

  Serial.begin(115200);
  WiFi.mode(WIFI_OFF);
  delay(100);
  WiFi.mode(WIFI_STA);

  WiFi.begin(ssid, password);
 
  while (WiFi.status() != WL_CONNECTED) {
    //col//baris
  lcd.setCursor(0,0);
  lcd.print("Menghubungkan Wifi");
  lcd.setCursor(0,1);
  lcd.print("SSID : ");
  lcd.setCursor(7,1);
  lcd.print(ssid);
  lcd.setCursor(1,2);
  lcd.print("Harap Tunggu.....");
  }
  lcd.clear();
  lcd.setCursor(0,0);
  lcd.print("Berhasil Terhubung");
  lcd.setCursor(0,1);
  lcd.print("SSID : ");
  lcd.setCursor(7,1);
  lcd.print(ssid);
  lcd.setCursor(0,3);
  lcd.print(WiFi.localIP());
  SPI.begin();
  mfrc522.PCD_Init();
  delay(2000);
  lcd.clear();
}

void loop() {

  if (WiFi.status() != WL_CONNECTED) {
    WiFi.disconnect();
    WiFi.mode(WIFI_STA);
    Serial.print("Reconnecting to ");
    Serial.println(ssid);
    WiFi.begin(ssid, password);

    while (WiFi.status() != WL_CONNECTED) {
      delay(500);
      Serial.print(".");
    }

    Serial.println("");
    Serial.println("Connected");
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());
  }
  
  for (byte i = 0; i < 6; i++) {
    key.keyByte[i] = 0xFF;
  }
  lcd.setCursor(3,1);
  lcd.print("Harap Tempelkan ");
  lcd.setCursor(4,2);
  lcd.print("Kartu Anda");

  if (!mfrc522.PICC_IsNewCardPresent()) return;
  if (!mfrc522.PICC_ReadCardSerial()) return;
  for (byte i = 0; i < mfrc522.uid.size; i++) {
    CardID += mfrc522.uid.uidByte[i];
  }
  lcd.clear();
  HTTPClient http;
  WiFiClient client;
  const int httpPort = 34226;

  if (!client.connect(host, httpPort)) {
    Serial.println("Connection Failed");
    return;
  }

  lcd.setCursor(2,1);
  lcd.print("HARAP TUNGGU...");
  lcd.setCursor(2,2);
  lcd.print("TETAP TEMPELKAN");

  Link1 = "http://180.ip.ply.gg:34226/adddata.php?nokartu=" + CardID;  //LocalHost
  http.begin(Link1);
  httpCode = http.GET();
  payload1 = http.getString();
  delay(10);

  if (payload1.length() == 15) {
    lcd.clear();
    Serial.println("Write Mode");
    Serial.println(httpCode);
    Serial.println(payload1);
    payload1.getBytes(blockData, 16);
    WriteDataToBlock(blockNum, blockData);
    lcd.setCursor(3,1);
    lcd.print("PROSES WRITE");
    lcd.setCursor(3,2);
    lcd.print("DATA BERHASIL");
    Serial.println("Berhasil Write Data");
    delay(100);
    ReadDataFromBlock(blockNum, readBlockData);
    for (int j = 0; j < 15; j++) {
      NIM += (char)readBlockData[j];  //convert byte[] to string
    }
    Link1 = "http://180.ip.ply.gg:34226/adddata.php?nokartu=" + NIM;  //LocalHost
    http.begin(Link1);
    httpCode = http.GET();
    payload1 = http.getString();
    Serial.println();
    Serial.println(payload1);
    delay(2000);
    lcd.clear();

  } else {
    lcd.clear();
    Serial.println("Read Mode");
    ReadDataFromBlock(blockNum, readBlockData);
    for (int j = 0; j < 15; j++) {
      NIM += (char)readBlockData[j];  //convert byte[] to string
    }
    lcd.setCursor(1,0);
    lcd.print("Proses verifikasi");
    lcd.setCursor(1,1);
    lcd.print("Lepas kartu anda");
    lcd.setCursor(1,2);
    lcd.print("Mohon ditunggu...");

    HTTPClient http2;
    Link2 = "http://180.ip.ply.gg:34226/terimakartu.php?nokartu=" + NIM ;  //LocalHost
    http2.begin(Link2);
    httpCode = http2.GET();
    payload2 = http2.getString();
    Serial.println(httpCode);
    Serial.println(payload2);
    Serial.println(NIM);

    Link2 = "http://180.ip.ply.gg:34226/notif.php";  //LocalHost
    http2.begin(Link2);
    httpCode = http2.GET();
    payload2 = http2.getString();
    Serial.println(httpCode);
    Serial.println(payload2);
    delay(2000);

    lcd.clear();
    if(payload2.equals("1")){
      lcd.setCursor(4,1);
      lcd.print("KARTU TIDAK");
      lcd.setCursor(5,2);
      lcd.print("TERDAFTAR");
    } else if(payload2.equals("2")){
      lcd.setCursor(2,1);
      lcd.print("TIDAK ADA JADWAL");
      lcd.setCursor(6,2);
      lcd.print("HARI INI");
    } else if(payload2.equals("3")){
      lcd.setCursor(2,1);
      lcd.print("TIDAK ADA KELAS");
      lcd.setCursor(1,2);
      lcd.print("PADA JAM SEKARANG");
    } else if(payload2.equals("4")){
      lcd.setCursor(1,1);
      lcd.print("SUDAH PERNAH ABSEN");
      lcd.setCursor(4,2);
      lcd.print("SEBELUMNYA");
    } else if(payload2.equals("5")){
      lcd.setCursor(2,1);
      lcd.print("ABSENSI BERHASIL");
      lcd.setCursor(5,2);
      lcd.print("DILAKUKAN");
    } else if(payload2.equals("6")){
      lcd.setCursor(3,0);
      lcd.print("Absensi Lebih");
      lcd.setCursor(3,1);
      lcd.print("Awal Berhasil");
      lcd.setCursor(5,2);
      lcd.print("Dilakukan");
    } else if(payload2.equals("7")){
      lcd.setCursor(1,0);
      lcd.print("Absensi Lebih");
      lcd.setCursor(1,1);
      lcd.print("Awal Hanya Bisa");
      lcd.setCursor(1,2);
      lcd.print("Dilakukan 30 Menit");
      lcd.setCursor(1,3);
      lcd.print("Sebelum Praktikum");
    } else {
      lcd.setCursor(2,1);
      lcd.print("ABSENSI GAGAL");
      lcd.setCursor(3,2);
      lcd.print("COBA LAGI...");
    }
    delay(5000);
    lcd.clear();
  }
  CardID = "";
  NIM = "";
  http.end();
  delay(2000);
  memset(readBlockData, 0, sizeof(readBlockData[0]) * bufferLen);
  mfrc522.PICC_HaltA();
  mfrc522.PCD_StopCrypto1();
}
void WriteDataToBlock(int blockNum, byte blockData[]) {
  status = mfrc522.PCD_Authenticate(MFRC522::PICC_CMD_MF_AUTH_KEY_A, blockNum, &key, &(mfrc522.uid));
  if (status != MFRC522::STATUS_OK) {
    Serial.print("Authentication failed for Write: ");
    Serial.println(mfrc522.GetStatusCodeName(status));
    return;
  } else {
    Serial.println("Authentication success");
  }

  /* Write data to the block */
  status = mfrc522.MIFARE_Write(blockNum, blockData, 16);
  if (status != MFRC522::STATUS_OK) {
    Serial.print("Writing to Block failed: ");
    Serial.println(mfrc522.GetStatusCodeName(status));
    return;
  } else {
    Serial.println("Data was written into Block successfully");
  }
}
void ReadDataFromBlock(int blockNum, byte readBlockData[]) {
  status = mfrc522.PCD_Authenticate(MFRC522::PICC_CMD_MF_AUTH_KEY_A, blockNum, &key, &(mfrc522.uid));
  if (status != MFRC522::STATUS_OK) {
    Serial.print("Authentication failed for Read: ");
    Serial.println(mfrc522.GetStatusCodeName(status));
    return;
  } else {
    Serial.println("Authentication success");
  }
  status = mfrc522.MIFARE_Read(blockNum, readBlockData, &bufferLen);
  if (status != MFRC522::STATUS_OK) {
    Serial.print("Reading failed: ");
    Serial.println(mfrc522.GetStatusCodeName(status));
    return;
  } else {
    Serial.println("Block was read successfully");
  }
}
