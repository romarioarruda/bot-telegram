### bot-telegram
Essa é uma automação para coleta de dados da loteria e envio dos dados via Telegram.

Para adicionar o bot a sua lista de contatos no Telegram, vá em 'Buscar' e pesquise por `@romarioloteriabot`.

Para executar a busca, clique no botão start ou digite a mensagem `/start` e envie para o bot.

Para relatar algum erro ou melhoria, entre em contato pelo email: `romarioarruda98@gmail.com`


### Clone do projeto

Para fazer o clone deste projeto, execute em seu terminal o comando:
`git clone https://github.com/romarioarruda/bot-telegram`

### Sua versão do bot
Caso você queira desenvolver sua própria versão deste bot, será necessário seguir os seguintes passos:

1 - Criar uma conta no telegram e acessar através do app mobile ou web.
2 - Adicionar aos seu contatos o `@BotFather` que é o responsável por criar novos bots na plataforma.
3 - envie o comando `/start` no campo de mensagem. Será o primeiro passo para a crição do seu bot.
4 - envie o comando `/newbot` no campo de mensagem. Será o comando para criar um novo bot.

Pra saber mais, a instrução `/help` deve ajudar.

Ao finalizar as intruções e concluir a criação do bot, será obtido um token de acesso.

### Seu ajuste no projeto
Para funcionar seguindo a estrutura desse projeto, crie um arquivo `env.ini` na pasta onde o arquivo loterias.php está.

E adicione a seguinte informação:

`token = 'seu_token'`

Será necessário hospedar o projeto com ssl ativo(https).

Para a segurança da automação, é necessário ter um https ativo.

Ative seu token e script que executa o envio e validação das mensagens, acessando a url abaixo:
`https://api.telegram.org/bot(seu_token)/setwebhook?url=https://linkdoseuseubot/script.php`

Feito isso, você será capaz de interagir com seu bot através do Telegram.

Para mais informações, acesse o manual:
`https://core.telegram.org/bots`

