from fecha import date 
from fecha import hr
import os
import requests 
#si marca error por no encontrarlo tiene que instalar el paquete poniendo:'pip install requests' en el cmd

if __name__ == "__main__":


    f_url = date()#llamada a fecha con el formato exacto actual
    hora  = hr()

    url = 'http://api.mercadopublico.cl/servicios/v1/publico/licitaciones.json'
    agrs = {'fecha':f_url,'ticket':'319CF43E-C87A-4D50-9581-DD1B6C79B9E8'}
    
    response = requests.get(url, params=agrs)
    cont = response.content#guardo el contenido de la pagina
    
    file = open(f"{os.getcwd()}/Capturas/Data_dia_{f_url}_hr_{hora}.json", "wb")
    file.write(cont)
    file.close

