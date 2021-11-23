# Marvel Exercise

https://developer.marvel.com/

## statement

Créer une petite application web exploitant

l'[API Marvel](https://developer.marvel.com/docs) et offrant les fonctionnalités
suivantes:

- Listing de l'un des types de contenus disponibles sur l'API (characters,
comics, creators...).

- Affichage de détails concernant l'entrée quand on clique dessus. Inutile
d'afficher toutes les infos disponibles, quelques informations pertinentes
sont suffisantes.

Le choix du stack technique et des librairies est libre. Le projet peut
nécessiter un serveur web. Le seul impératif étant que nous devons être capable
d'installer l'application facilement puis de l'ouvrir dans un explorateur sans
problème.

### Connexion à l'API Marvel

Les informations suivantes peuvent être utilisées pour se connecter à l'API
Marvel:

**public key**: `******`

**private key**: `******`

La documentation complète est disponible sur
[](https://developer.marvel.com/)[https://developer.marvel.com](https://developer.marvel.com/)


## Project setup

1) edit root project .env.exemple after renaming to .env

```
# Will be shown as server proxy name in settings page
VUE_APP_SERVER_NAME 

# Full url of the path where you put on your server the "server proxy" folder (found in project root)
VUE_APP_SERVER_URL

# Full url of the path where you put the dist folder after compiling the app (found in project root)
VUE_APP_BASE_URL

# Your marvel api keys you can obtain here: https://developer.marvel.com/account
# also set in marvel settings your server url
VUE_APP_MARVEL_PRIVATE_KEY
VUE_APP_MARVEL_PUBLIC_KEY
```

2) edit "server proxy" folder .env.exemple after renaming to .env

```
# on local server you should put the result of php 
# <?= $_SERVER['REMOTE_ADDR'].':'.$_SERVER['SERVER_PORT'] ?>
ACCESS-CONTROL-ALLOW-ORIGIN=your server origin or empty 

# Your marvel api keys you can obtain here: https://developer.marvel.com/account
# also set in marvel settings your server url
MARVEL_API_PUBLIC_KEY= ******
MARVEL_API_PRIVATE_KEY= ******
```

3) Install node

```
https://nodejs.org/

```

4) Install npm

```

npm install -g @vue/cli @vue/cli-service-global

# or

yarn global add @vue/cli @vue/cli-service-global

```

5) Start console in project root

```

npm install

```

### Compiles and hot-reloads for development

```

vue ui

# import the project -> Import tab -> select project root directory -> import button 

# then in browser -> Tasks tab -> serve -> launch task

# or

npm run serve

```



### Compiles and minifies for production

```

vue ui

# then in browser -> Tasks tab -> build -> launch task

# or

npm run build

```


### Deploy app on the server

```

# Copy "dist" and "server proxy" folders to your server
# Go to the url path of where you put the dist folder content 
# exemple: https://dev.nextweb.ch/marvel/dist
# Go to settings tab and configure if you want to use server proxy option or direct calls to the Marvel API
# You can also configure if you want to see debug infos
# enjoy

```


### Lints and fixes files

```

npm run lint

```

### Customize configuration

See [Configuration Reference](https://cli.vuejs.org/config/).


### Project dependencies tab added:

```
axios: for api consuming

vue-router: for app navigation

vuex: for state management

vuex-persist: for persisting user data accross app visits

```
