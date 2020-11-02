import datetime


def SCD(d): #suma 0 en el principio de un dia en caso de que este sea menor a 10
    if d < 10:

        di = (f'0{d}')
        return di

    else:
        return d
def SCM(m): #suma 0 en el principio de un mes en caso de que este sea menor a 10
    if m < 10:

        me = (f'0{m}')
        return me

    else:
        return m
 


def date():

    a    = datetime.datetime.now() #Extraigo la fecha del día Ej: 3 09 2020
    d    = a.day
    m    = a.month
    agno = a.year

    #retorno de valores de SCD y SCM (sumas de 0 en caso de ser necesario)
    dia = SCD(d) 
    mes = SCM(m)

    date_now = (f'{dia}{mes}{agno}')

    return date_now #esta funcion se encargara de retornar la fecha en el formato para la url

def hr(): #retorna un formato de hora para el guardado del archivo
   
    a   = datetime.datetime.now()
    hor = (f'{a.hour}_{a.minute}')
    return hor

