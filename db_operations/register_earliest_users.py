import random
import string
import requests
import psycopg2
import pandas as pd
from sqlalchemy import create_engine
from psycopg2.extensions import ISOLATION_LEVEL_AUTOCOMMIT

token_api  = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IlFqVkNRamc0T1VVM05FUkJNRUUxUXpoRk5URTVOak5GUVVJNU56SkZNek0xT0VFM1EwRkZRUSJ9.eyJpc3MiOiJodHRwczovL2Rldi10c2R4LWxjNy5hdXRoMC5jb20vIiwic3ViIjoiZjlaWWdZZFdJVE5LT24wS1pDNUduTjBwTnZKVnRjSThAY2xpZW50cyIsImF1ZCI6InVybjphdXRoMC1hdXRoei1hcGkiLCJpYXQiOjE1ODgyMjQ3NzEsImV4cCI6MTU4ODMxMTE3MSwiYXpwIjoiZjlaWWdZZFdJVE5LT24wS1pDNUduTjBwTnZKVnRjSTgiLCJzY29wZSI6InJlYWQ6dXNlcnMgcmVhZDphcHBsaWNhdGlvbnMgcmVhZDpjb25uZWN0aW9ucyByZWFkOmNvbmZpZ3VyYXRpb24gdXBkYXRlOmNvbmZpZ3VyYXRpb24gcmVhZDpncm91cHMgY3JlYXRlOmdyb3VwcyB1cGRhdGU6Z3JvdXBzIGRlbGV0ZTpncm91cHMgcmVhZDpyb2xlcyBjcmVhdGU6cm9sZXMgdXBkYXRlOnJvbGVzIGRlbGV0ZTpyb2xlcyByZWFkOnBlcm1pc3Npb25zIGNyZWF0ZTpwZXJtaXNzaW9ucyB1cGRhdGU6cGVybWlzc2lvbnMgZGVsZXRlOnBlcm1pc3Npb25zIHJlYWQ6cmVzb3VyY2Utc2VydmVyIGNyZWF0ZTpyZXNvdXJjZS1zZXJ2ZXIgdXBkYXRlOnJlc291cmNlLXNlcnZlciBkZWxldGU6cmVzb3VyY2Utc2VydmVyIiwiZ3R5IjoiY2xpZW50LWNyZWRlbnRpYWxzIn0.Zjw7XddYOk-a9JpmnFbaSo95XYhn3FG-ci64pRo1GXCReK-jW0KW9Ur9FTTQ-EQXqrop2ak4tJWp7xIO4IPHcQVsWfilxyEKgzIxq_UtzlEwwNTWQ7wZhsCb2flbZ6K4eEcx0d2_VyKn5WCko4S4mVDkuyFSjAAWRMbl4tX336dmY2Y_nwcaozYMkBG8-C2SfsglqMpYCkUhe9MBlQ_0WALVph5IlGnS4fShFbe-9j2tS5TfXfjPW6c4roias9H3nP3PrK7EH_QDm2zjhF7mPdhdrZRErcKX35psl4TJu3GC6wSHbxoK-SH5MDbFcjao02TXN_NSKjX2_ke9rC0ElQ'
token      = 'eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCIsImtpZCI6IlFqVkNRamc0T1VVM05FUkJNRUUxUXpoRk5URTVOak5GUVVJNU56SkZNek0xT0VFM1EwRkZRUSJ9.eyJpc3MiOiJodHRwczovL2Rldi10c2R4LWxjNy5hdXRoMC5jb20vIiwic3ViIjoiUTQ0amlXZmZFNmV2MlFwc1VpY1FSTXFNM3FaajR3aHhAY2xpZW50cyIsImF1ZCI6Imh0dHBzOi8vZGV2LXRzZHgtbGM3LmF1dGgwLmNvbS9hcGkvdjIvIiwiaWF0IjoxNTg4MjIzMzMyLCJleHAiOjE1ODgzMDk3MzIsImF6cCI6IlE0NGppV2ZmRTZldjJRcHNVaWNRUk1xTTNxWmo0d2h4Iiwic2NvcGUiOiJyZWFkOmNsaWVudF9ncmFudHMgY3JlYXRlOmNsaWVudF9ncmFudHMgZGVsZXRlOmNsaWVudF9ncmFudHMgdXBkYXRlOmNsaWVudF9ncmFudHMgcmVhZDp1c2VycyB1cGRhdGU6dXNlcnMgZGVsZXRlOnVzZXJzIGNyZWF0ZTp1c2VycyByZWFkOnVzZXJzX2FwcF9tZXRhZGF0YSB1cGRhdGU6dXNlcnNfYXBwX21ldGFkYXRhIGRlbGV0ZTp1c2Vyc19hcHBfbWV0YWRhdGEgY3JlYXRlOnVzZXJzX2FwcF9tZXRhZGF0YSByZWFkOnVzZXJfY3VzdG9tX2Jsb2NrcyBjcmVhdGU6dXNlcl9jdXN0b21fYmxvY2tzIGRlbGV0ZTp1c2VyX2N1c3RvbV9ibG9ja3MgY3JlYXRlOnVzZXJfdGlja2V0cyByZWFkOmNsaWVudHMgdXBkYXRlOmNsaWVudHMgZGVsZXRlOmNsaWVudHMgY3JlYXRlOmNsaWVudHMgcmVhZDpjbGllbnRfa2V5cyB1cGRhdGU6Y2xpZW50X2tleXMgZGVsZXRlOmNsaWVudF9rZXlzIGNyZWF0ZTpjbGllbnRfa2V5cyByZWFkOmNvbm5lY3Rpb25zIHVwZGF0ZTpjb25uZWN0aW9ucyBkZWxldGU6Y29ubmVjdGlvbnMgY3JlYXRlOmNvbm5lY3Rpb25zIHJlYWQ6cmVzb3VyY2Vfc2VydmVycyB1cGRhdGU6cmVzb3VyY2Vfc2VydmVycyBkZWxldGU6cmVzb3VyY2Vfc2VydmVycyBjcmVhdGU6cmVzb3VyY2Vfc2VydmVycyByZWFkOmRldmljZV9jcmVkZW50aWFscyB1cGRhdGU6ZGV2aWNlX2NyZWRlbnRpYWxzIGRlbGV0ZTpkZXZpY2VfY3JlZGVudGlhbHMgY3JlYXRlOmRldmljZV9jcmVkZW50aWFscyByZWFkOnJ1bGVzIHVwZGF0ZTpydWxlcyBkZWxldGU6cnVsZXMgY3JlYXRlOnJ1bGVzIHJlYWQ6cnVsZXNfY29uZmlncyB1cGRhdGU6cnVsZXNfY29uZmlncyBkZWxldGU6cnVsZXNfY29uZmlncyByZWFkOmhvb2tzIHVwZGF0ZTpob29rcyBkZWxldGU6aG9va3MgY3JlYXRlOmhvb2tzIHJlYWQ6ZW1haWxfcHJvdmlkZXIgdXBkYXRlOmVtYWlsX3Byb3ZpZGVyIGRlbGV0ZTplbWFpbF9wcm92aWRlciBjcmVhdGU6ZW1haWxfcHJvdmlkZXIgYmxhY2tsaXN0OnRva2VucyByZWFkOnN0YXRzIHJlYWQ6dGVuYW50X3NldHRpbmdzIHVwZGF0ZTp0ZW5hbnRfc2V0dGluZ3MgcmVhZDpsb2dzIHJlYWQ6c2hpZWxkcyBjcmVhdGU6c2hpZWxkcyBkZWxldGU6c2hpZWxkcyByZWFkOmFub21hbHlfYmxvY2tzIGRlbGV0ZTphbm9tYWx5X2Jsb2NrcyB1cGRhdGU6dHJpZ2dlcnMgcmVhZDp0cmlnZ2VycyByZWFkOmdyYW50cyBkZWxldGU6Z3JhbnRzIHJlYWQ6Z3VhcmRpYW5fZmFjdG9ycyB1cGRhdGU6Z3VhcmRpYW5fZmFjdG9ycyByZWFkOmd1YXJkaWFuX2Vucm9sbG1lbnRzIGRlbGV0ZTpndWFyZGlhbl9lbnJvbGxtZW50cyBjcmVhdGU6Z3VhcmRpYW5fZW5yb2xsbWVudF90aWNrZXRzIHJlYWQ6dXNlcl9pZHBfdG9rZW5zIGNyZWF0ZTpwYXNzd29yZHNfY2hlY2tpbmdfam9iIGRlbGV0ZTpwYXNzd29yZHNfY2hlY2tpbmdfam9iIHJlYWQ6Y3VzdG9tX2RvbWFpbnMgZGVsZXRlOmN1c3RvbV9kb21haW5zIGNyZWF0ZTpjdXN0b21fZG9tYWlucyByZWFkOmVtYWlsX3RlbXBsYXRlcyBjcmVhdGU6ZW1haWxfdGVtcGxhdGVzIHVwZGF0ZTplbWFpbF90ZW1wbGF0ZXMgcmVhZDptZmFfcG9saWNpZXMgdXBkYXRlOm1mYV9wb2xpY2llcyByZWFkOnJvbGVzIGNyZWF0ZTpyb2xlcyBkZWxldGU6cm9sZXMgdXBkYXRlOnJvbGVzIHJlYWQ6cHJvbXB0cyB1cGRhdGU6cHJvbXB0cyByZWFkOmJyYW5kaW5nIHVwZGF0ZTpicmFuZGluZyByZWFkOmxvZ19zdHJlYW1zIGNyZWF0ZTpsb2dfc3RyZWFtcyBkZWxldGU6bG9nX3N0cmVhbXMgdXBkYXRlOmxvZ19zdHJlYW1zIGNyZWF0ZTpzaWduaW5nX2tleXMgcmVhZDpzaWduaW5nX2tleXMgdXBkYXRlOnNpZ25pbmdfa2V5cyIsImd0eSI6ImNsaWVudC1jcmVkZW50aWFscyJ9.izKtmua7DRHwBSeO5t6WMD5sbhFlMPCD2cp7DH0BEmcAvESgkgAbVbtIzXsOQGsPAKvzm1K8sagDVefyhwdqmT7vcjn5a-jTXb4fybAznQiJHyeJidF6K4XnLcaYroeq9FMNgqZK2gH8A0Flw5AvHfBM1vFAczy3UdyeZNZAhumq1utduHE3qKWm6R4fa4m0P2rEp-9_SDJ87y9RKjCziVLNqTmT9NgyUvogLYjwk82lB8Dr0ZCCnw4ell3yLwxZtnjbk3ypVlFMowEV2XcpxoSV2T1e3Jpsd-Js37TJwH0_lsvX_BiEWcrSdjtfVUeAbEgUvGknugsykR_WR8yCJQ'
url        = 'https://dev-tsdx-lc7.auth0.com/api/v2/'
url_api    = "https://dev-tsdx-lc7.us.webtask.io/adf6e2f2b84784b57522e3b19dfc9201/api/";
headers    = {'content-type': 'application/json', 'authorization': 'Bearer ' + token}
headers_api= {'content-type': 'application/json', 'authorization': 'Bearer ' + token_api}
insertion = "INSERT INTO \"analistas\"(\"Persona\", \"Puesto\", \"mail\", \"id_auth0\", \"activo\", password) VALUES('{0}', '{1}', '{2}', '{3}', '1', '{4}');"



engine = create_engine('postgresql+psycopg2://{0}:{1}@{2}:{3}/{4}'.format('postgres', 'sig123456', '127.0.0.1', '5432', 'metadatos'), echo=False)



def randomString(stringLength=8):
    letters = string.ascii_lowercase
    return ''.join(random.choice(letters) for i in range(stringLength))



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
   register users on file: `usuarios_iniciales.xlsx` 
'''
df = pd.read_excel('./usuarios_iniciales.xlsx')
# ['idAnalista', 'Persona', 'Rol', 'mail']
print('registering users ...')

conn = psycopg2.connect('dbname=metadatos host=127.0.0.1 user=postgres password=sig123456 port=5432')
conn.set_isolation_level(ISOLATION_LEVEL_AUTOCOMMIT)

N = df.shape[0]

for i in range(N):
	person = df.iloc[i]['Persona']
	role = df.iloc[i]['Rol']
	email = df.iloc[i]['mail']
	#email = 'promero' + str(i) + '@gmail.com'

	names = person.split(' ')

	metadatos = {}
	metadatos['id_analista'] = str(df.iloc[i]['idAnalista'])
	password = randomString(6) + '-' + str(df.iloc[i]['idAnalista'] * 879)[-2:]

	if len(names) == 2:
		given_name = names[0]
		family_name = names[1]
	elif len(names) == 3:
		given_name = names[0]
		family_name = names[1] + ' ' + names[2]
	elif len(names) == 4:
		given_name = names[0] + ' ' + names[1]
		family_name = names[3] + ' ' + names[3]

	data = {
			'email': email,
			'given_name': given_name,
			'family_name': family_name,
		    'password': password,
			'connection': 'Username-Password-Authentication',
			'blocked': False,
			'user_metadata': metadatos
			}

	roles_map = {
				'Administrador de Metadatos': b'["ff367830-e97c-49ac-8c9a-aa441a64228d"]', 
				'Analista de Metadatos': b'["f927ea8d-93cc-495b-9c9e-963c20db3751"]',
				'Capturista de Metadatos': b'["6e1a363f-5b8b-40d4-a791-f0e5d1692ba5"]' 
				}

	response = requests.post(url + 'users', json=data, headers=headers).json()
	id_auth0 = response['user_id']
	
	print(response)

	response = requests.patch(url_api + 'users/' + id_auth0 + '/roles', roles_map[role], headers=headers_api)#.json()

	print(response)

	insertion_sql = insertion.format(person, role, email, id_auth0, password)
	print(insertion_sql)

	cur = conn.cursor()
	cur.execute(insertion_sql)
	cur.close()

