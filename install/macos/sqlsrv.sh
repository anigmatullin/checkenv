
brew install openssl@1.1

rm -f /usr/local/opt/openssl

ln -s /usr/local/Cellar/openssl@1.1/1.1.1l_1 /usr/local/opt/openssl

brew tap microsoft/mssql-release https://github.com/Microsoft/homebrew-mssql-release

brew update

HOMEBREW_NO_ENV_FILTERING=1 ACCEPT_EULA=Y brew install msodbcsql17 mssql-tools

sudo pecl install sqlsrv

sudo pecl install pdo_sqlsrv

