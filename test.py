import random as r

def generate_rotation(lst):
    # Génère une rotation aléatoire de la liste
    index = r.randint(0, len(lst) - 1)
    return lst[index:] + lst[:index]

donneurs = []
resultat = []
nbrpart = int(input("Combien de participants ? : "))

# Saisie des donneurs
for i in range(nbrpart):
    donneurs.append(input(f"Donneur {i + 1} : "))

# Génère une rotation aléatoire des donneurs
donneurs = generate_rotation(donneurs)

# Création des couples
for i in range(nbrpart - 1):
    resultat.append((donneurs[i], donneurs[i + 1]))

# Ajout du dernier couple formant une boucle
resultat.append((donneurs[-1], donneurs[0]))

# Affichage des résultats étape par étape
print("Attribution étape par étape :")
for donneur, receveur in resultat:
    print(f"{donneur} donne à {receveur}")

