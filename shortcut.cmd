if not defined in_subprocess (cmd /k set in_subprocess=y ^& %0 %*) & exit 

:: start /b npm run serve && start /b npm run build --watch

start /b vue ui