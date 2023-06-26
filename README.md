# tp_lacomanda PROGRAMACION 3
##TECNOLOGIAS UTILIZADAS
PHP + MicroFramewok SLIM, POSTMAN (envio de HTTP) y phpMyAdmin como administrador de MySQL.
Temas SLIM: Middleware, MVC, PDO, JWT y enrutamiento.
##Requerimientos:
Debemos realizar un sistema según las necesidades y deseos del cliente, para eso tenemos una breve descripción de lo que el cliente nos comenta acerca de su negocio.
“Mi restaurante tiene un serviciode la más altacalidad, con cuatro sectores biendiferenciados: labarra de tragos y vinos, en nuestra entrada; en el patio trasero se encuentra la barra de choperas de cerveza artesanal; la cocina, donde se preparan todos los platos de comida; y nuestro Candy Bar, que se encarga de la preparación de postres artesanales.
Dentro de nuestro plantel de trabajadores tenemos muchos empleados que son trabajadores golondrinas, por lo cual, tenemos mucha rotación de personal, pero los tenemos bien diferenciados entre los #bartender , los #cerveceros, los #cocineros, los #mozos y los que podemos controlar todo incluso los pagos, que somos cualquiera de los tres #socios del local.
Necesitamos que cada comanda tenga la información necesaria, incluso el nombre del cliente, y que sea vista por el empleado correspondiente. La operatoria principal sería:
Si al mozo le hacen un pedido de un vino, una cerveza y unas empanadas, deberían los
empleados correspondientes ver estos pedidos en su listado de “pendientes”, con la opción de
tomar una foto de la mesa con sus integrantes y relacionarlo con el pedido.
El mozo le da un código único alfanumérico (de 5 caracteres) al cliente que le permite identificar
su pedido.
El empleado que toma ese pedido para prepararlo, al momento de hacerlo, debe cambiar el
estado de ese pedido a “en preparación” y agregarle un tiempo estimado de finalización, teniendo
en cuenta que puede haber más de un empleado en el mismo puesto. Ej: dos bartender o tres
cocineros.
El empleado que toma ese pedido para prepararlo debe poner el estado “listo para servir”,
cuando el pedido esté listo.
Cualquiera de los socios puede ver, en todo momento, el estado de todos los pedidos.
Las mesas tienen un código de identificación único (de 5 caracteres) , el cliente al entrar en
nuestra aplicación puede ingresar ese código junto con el número del pedido y se le mostrará el
tiempo restante para su pedido.
Al terminar de comer se habilita una encuesta con una puntuación del 1 al 10 para:
● La mesa.
● El restaurante.
● El mozo.
● El cocinero.
Y un breve texto de hasta 66 caracteres describiendo la experiencia (buena o mala) que tuvo en su
atención.
