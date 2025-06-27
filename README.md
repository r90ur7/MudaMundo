# MudaMundo 🌱  

**Plataforma web para doação e distribuição de mudas de plantas**  
*(Projeto de TCC desenvolvido com Laravel)*  

---

## 🔍 Visão Geral  
Sistema que conecta doadores e receptores de mudas vegetais, facilitando o cultivo sustentável e a economia circular de recursos botânicos.  

---

## ✨ Funcionalidades  
- **Dashboard interativo** com filtros para espécies, localização e disponibilidade  
- Sistema de **favoritos** para acompanhamento de mudas  
- **Perfil de usuário** com histórico de doações/recebimentos  
- **Chat em tempo real** (via Pusher) para comunicação entre usuários  
- **Sistema de notificações** para atualizações de status  

---

## ⚙️ Tecnologias  

Laravel 10.x | PHP 8.1+ | Tailwind CSS | MySQL | Pusher (WebSockets) | Docker

---

## 🚀 Configuração do Ambiente  
1. **Pré-requisitos**:  
   - Docker Engine  
   - Node.js 18.x  

2. Clonar repositório:  
   ```bash  
   git clone https://github.com/r90ur7/MudaMundo.git
   docker compose up -d --build
   composer install && npm install
   cp .env.example .env
   php artisan migrate --seed

## 🌐 Acesso Online
A versão em produção está disponível em:

https://mudamundo.onrender.com

## ⚙️ strutura de Diretórios  

app/           → Lógica da aplicação  
config/        → Arquivos de configuração  
database/      → Migrações e seeds  
public/        → Assets compilados  
resources/     → Views e frontend  
routes/        → Definição de endpoints  
storage/       → Arquivos gerados pelo sistema  
## Licença
Distribuído sob licença MIT. Consulte LICENSE.md para detalhes.

### 🔍 Observações importantes:  
1. **Seções adaptáveis**: Adicione capturas de tela na seção `Funcionalidades` para melhor visualização  
2. **Badges personalizáveis**: Inclua indicadores de CI/CD (ex: GitHub Actions) conforme implementação  
3. **Documentação complementar**: Recomendo adicionar um `docs/` com:  
   - Diagrama de arquitetura  
   - User flows  
   - Casos de uso  
