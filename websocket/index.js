const httpServer = require('http').createServer()
const io = require("socket.io")(httpServer, {
 cors: {
 // The origin is the same as the Vue app domain. Change if necessary
 origin: "http://127.0.0.1:5173",
 methods: ["GET", "POST"],
 credentials: true
 }
})
httpServer.listen(8080, () =>{
 console.log('listening on *:8080')
})
io.on('connection', (socket) => {
        console.log(`client ${socket.id} has connected`)

        socket.on('newHotDish', (product) => {
            socket.in('chefs').emit('newHotDish', product)
        })
       
        socket.on('readyProduct', (product) => {
            console.log(`Prato ${product.id} pronto para o caixa`)
            socket.in('caixa').emit('readyProduct', product)
        })

        socket.on('readyOrder', function (order,id) {
            console.log(`Pedido ${order.id} pronto para o cliente ${id}`)
            socket.in(id).emit('readyOrder', order)
        })

        socket.on('loggedIn', function (user) {
            socket.join(user.id)
            if (user.type == 'EC') {
                console.log(`Entrou o chef: ${user.id}`)
                socket.join('chefs')
            }
            if(user.type == 'ED'){
                console.log(`Entrou um caixa: ${user.id}`)
                socket.join('caixa')
            }   
            })

            socket.on('loggedOut', function (user) {
            socket.leave(user.id)
            socket.leave('chefs')
            socket.leave('caixa')
            })
})