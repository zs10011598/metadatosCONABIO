import requests
import psycopg2
import pandas as pd
from sqlalchemy import create_engine
from psycopg2.extensions import ISOLATION_LEVEL_AUTOCOMMIT


token = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IlFqVkNRamc0T1VVM05FUkJNRUUxUXpoRk5URTVOak5GUVVJNU56SkZNek0xT0VFM1EwRkZRUSJ9.eyJpc3MiOiJodHRwczovL2Rldi10c2R4LWxjNy5hdXRoMC5jb20vIiwic3ViIjoiUTQ0amlXZmZFNmV2MlFwc1VpY1FSTXFNM3FaajR3aHhAY2xpZW50cyIsImF1ZCI6Imh0dHBzOi8vZGV2LXRzZHgtbGM3LmF1dGgwLmNvbS9hcGkvdjIvIiwiaWF0IjoxNTg4MjE5NzI4LCJleHAiOjE1ODgzMDYxMjgsImF6cCI6IlE0NGppV2ZmRTZldjJRcHNVaWNRUk1xTTNxWmo0d2h4Iiwic2NvcGUiOiJyZWFkOmNsaWVudF9ncmFudHMgY3JlYXRlOmNsaWVudF9ncmFudHMgZGVsZXRlOmNsaWVudF9ncmFudHMgdXBkYXRlOmNsaWVudF9ncmFudHMgcmVhZDp1c2VycyB1cGRhdGU6dXNlcnMgZGVsZXRlOnVzZXJzIGNyZWF0ZTp1c2VycyByZWFkOnVzZXJzX2FwcF9tZXRhZGF0YSB1cGRhdGU6dXNlcnNfYXBwX21ldGFkYXRhIGRlbGV0ZTp1c2Vyc19hcHBfbWV0YWRhdGEgY3JlYXRlOnVzZXJzX2FwcF9tZXRhZGF0YSByZWFkOnVzZXJfY3VzdG9tX2Jsb2NrcyBjcmVhdGU6dXNlcl9jdXN0b21fYmxvY2tzIGRlbGV0ZTp1c2VyX2N1c3RvbV9ibG9ja3MgY3JlYXRlOnVzZXJfdGlja2V0cyByZWFkOmNsaWVudHMgdXBkYXRlOmNsaWVudHMgZGVsZXRlOmNsaWVudHMgY3JlYXRlOmNsaWVudHMgcmVhZDpjbGllbnRfa2V5cyB1cGRhdGU6Y2xpZW50X2tleXMgZGVsZXRlOmNsaWVudF9rZXlzIGNyZWF0ZTpjbGllbnRfa2V5cyByZWFkOmNvbm5lY3Rpb25zIHVwZGF0ZTpjb25uZWN0aW9ucyBkZWxldGU6Y29ubmVjdGlvbnMgY3JlYXRlOmNvbm5lY3Rpb25zIHJlYWQ6cmVzb3VyY2Vfc2VydmVycyB1cGRhdGU6cmVzb3VyY2Vfc2VydmVycyBkZWxldGU6cmVzb3VyY2Vfc2VydmVycyBjcmVhdGU6cmVzb3VyY2Vfc2VydmVycyByZWFkOmRldmljZV9jcmVkZW50aWFscyB1cGRhdGU6ZGV2aWNlX2NyZWRlbnRpYWxzIGRlbGV0ZTpkZXZpY2VfY3JlZGVudGlhbHMgY3JlYXRlOmRldmljZV9jcmVkZW50aWFscyByZWFkOnJ1bGVzIHVwZGF0ZTpydWxlcyBkZWxldGU6cnVsZXMgY3JlYXRlOnJ1bGVzIHJlYWQ6cnVsZXNfY29uZmlncyB1cGRhdGU6cnVsZXNfY29uZmlncyBkZWxldGU6cnVsZXNfY29uZmlncyByZWFkOmhvb2tzIHVwZGF0ZTpob29rcyBkZWxldGU6aG9va3MgY3JlYXRlOmhvb2tzIHJlYWQ6ZW1haWxfcHJvdmlkZXIgdXBkYXRlOmVtYWlsX3Byb3ZpZGVyIGRlbGV0ZTplbWFpbF9wcm92aWRlciBjcmVhdGU6ZW1haWxfcHJvdmlkZXIgYmxhY2tsaXN0OnRva2VucyByZWFkOnN0YXRzIHJlYWQ6dGVuYW50X3NldHRpbmdzIHVwZGF0ZTp0ZW5hbnRfc2V0dGluZ3MgcmVhZDpsb2dzIHJlYWQ6c2hpZWxkcyBjcmVhdGU6c2hpZWxkcyBkZWxldGU6c2hpZWxkcyByZWFkOmFub21hbHlfYmxvY2tzIGRlbGV0ZTphbm9tYWx5X2Jsb2NrcyB1cGRhdGU6dHJpZ2dlcnMgcmVhZDp0cmlnZ2VycyByZWFkOmdyYW50cyBkZWxldGU6Z3JhbnRzIHJlYWQ6Z3VhcmRpYW5fZmFjdG9ycyB1cGRhdGU6Z3VhcmRpYW5fZmFjdG9ycyByZWFkOmd1YXJkaWFuX2Vucm9sbG1lbnRzIGRlbGV0ZTpndWFyZGlhbl9lbnJvbGxtZW50cyBjcmVhdGU6Z3VhcmRpYW5fZW5yb2xsbWVudF90aWNrZXRzIHJlYWQ6dXNlcl9pZHBfdG9rZW5zIGNyZWF0ZTpwYXNzd29yZHNfY2hlY2tpbmdfam9iIGRlbGV0ZTpwYXNzd29yZHNfY2hlY2tpbmdfam9iIHJlYWQ6Y3VzdG9tX2RvbWFpbnMgZGVsZXRlOmN1c3RvbV9kb21haW5zIGNyZWF0ZTpjdXN0b21fZG9tYWlucyByZWFkOmVtYWlsX3RlbXBsYXRlcyBjcmVhdGU6ZW1haWxfdGVtcGxhdGVzIHVwZGF0ZTplbWFpbF90ZW1wbGF0ZXMgcmVhZDptZmFfcG9saWNpZXMgdXBkYXRlOm1mYV9wb2xpY2llcyByZWFkOnJvbGVzIGNyZWF0ZTpyb2xlcyBkZWxldGU6cm9sZXMgdXBkYXRlOnJvbGVzIHJlYWQ6cHJvbXB0cyB1cGRhdGU6cHJvbXB0cyByZWFkOmJyYW5kaW5nIHVwZGF0ZTpicmFuZGluZyByZWFkOmxvZ19zdHJlYW1zIGNyZWF0ZTpsb2dfc3RyZWFtcyBkZWxldGU6bG9nX3N0cmVhbXMgdXBkYXRlOmxvZ19zdHJlYW1zIGNyZWF0ZTpzaWduaW5nX2tleXMgcmVhZDpzaWduaW5nX2tleXMgdXBkYXRlOnNpZ25pbmdfa2V5cyIsImd0eSI6ImNsaWVudC1jcmVkZW50aWFscyJ9.NYsmrsW17mBxlI8fbmKRNslzyLwD-v7WT8DrMp-CFVMgPQ_dz6KMGJVF0MWl55snoMVezj5DT1l7VrFOWjAlrIOGZvkKr0sVSrtkDiLfjTdsyquhFOX36RbAMozCB2m23xk0MpW_aIHTW6q30w62ZzSDqudkcdiDq5A2EPuKBPh_g-GITrn4wnWbTe-SWN39WhK2f7g705cixZWJ0mToAsUuIkyv_Oupms7rx9aDPgcMB8lJqCyb3wqHYY_ic00GidHzb8zY-RPAQ62Hb787jqwp3DAIg9-w5jBBzwNZ4nirRFG5Y2lFRGkFx0LJ2-s21qzyehDm4QXH2NliKvwF3g'
url = 'https://dev-tsdx-lc7.auth0.com/api/v2/'
headers = {'content-type': 'application/json', 'authorization': 'Bearer ' + token}


engine = create_engine('postgresql+psycopg2://{0}:{1}@{2}:{3}/{4}'.format('postgres', 'sig123456', '127.0.0.1', '5432', 'metadatos'), echo=False)

df = pd.read_sql(sql='SELECT * FROM analistas', con=engine, index_col='idAnalista')

index = df.index


'''
	get users
'''
response = requests.get(url + 'users', headers=headers).json()		
users = list(response)
print('Users: ' + str(len(users)))

for user in users:
	response = requests.delete(url + 'users/' + user['user_id'], headers=headers)
	print(response)
	pass



'''
	register users
'''
print('registering users ...')
conn = psycopg2.connect('dbname=metadatos host=127.0.0.1 user=postgres password=sig123456 port=5432')
conn.set_isolation_level(ISOLATION_LEVEL_AUTOCOMMIT)

for i in list(index):
	nombre_completo = df.loc[i]['Persona']
	nombre_completo = nombre_completo.split(' ')
	metadatos = {}

	metadatos['id_analista'] = str(i)

	if '' in nombre_completo:
		nombre_completo.remove('')

	#print(nombre_completo)

	if len(nombre_completo) == 2:

		given_name = nombre_completo[0]
		family_name = nombre_completo[1]

	elif len(nombre_completo) == 3:

		if nombre_completo[2][0] == '(':
			given_name = nombre_completo[0]  
			family_name = nombre_completo[1]
		else:
			given_name = nombre_completo[0]
			family_name = nombre_completo[1] + ' ' + nombre_completo[2]

	elif len(nombre_completo) == 4:

		if nombre_completo[3][0] == '(':
			given_name = nombre_completo[0] 
			family_name = nombre_completo[1] + ' ' + nombre_completo[2]
		else:
			given_name = nombre_completo[0] + ' ' + nombre_completo[1]
			family_name = nombre_completo[2] + ' ' + nombre_completo[3]

	#email = df.loc[i]['mail']
	email = 'p' + str(i) + 'ssig@conabio.gob.mx'
	password = 'M3t4d4t0$'
	phone = df.loc[i]['Telefono']
	active = df.loc[i]['activo']

	data = {
			'email': email,
			'given_name': given_name,
			'family_name': family_name,
		    'password': password,
			'connection': 'Username-Password-Authentication',
			'blocked': str(active) != '1',
			'user_metadata': metadatos 

			}

	#if phone != None:
	#	data['phone_number'] = '+52'+''.join(phone[2:].split(' '))

	response = requests.post(url + 'users', json=data, headers=headers).json()

	#print(i, given_name, family_name, email, response)

	user_id = response['user_id']

	update_sql = "UPDATE analistas SET id_auth0='{0}' WHERE \"idAnalista\" = ".format(user_id) + str(i)
	print(update_sql)

	cur = conn.cursor()
	cur.execute(update_sql)
	cur.close()


conn.close()