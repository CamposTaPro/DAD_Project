const httpServer = require("http").createServer();
const io = require("socket.io")(httpServer, {
  cors: {
    // The origin is the same as the Vue app domain. Change if necessary
    origin: "http://127.0.0.1:5173",
    methods: ["GET", "POST"],
    credentials: true,
  },
});

httpServer.listen(8080, () => {});

io.on("connection", (socket) => {
  socket.on("newHotDish", (product) => {
    socket.in("chefs").emit("newHotDish", product);
  });

  socket.on("readyProduct", (product) => {
    socket.in("caixa").emit("readyProduct", product);
  });

  socket.on("readyOrder", function (order, id) {
    socket.in(id).emit("readyOrder", order);
  });

  socket.on("readyOrderPublic", function (order) {
    socket.broadcast.emit("readyOrderPublic", order);
  });

  socket.on("deliverOrderPublic", function (order) {
    socket.broadcast.emit("deliverOrderPublic", order);
  });

  socket.on("newOrder", function (order) {
    socket.broadcast.emit('newOrder', order)
  });

  socket.on("blockOrUnblockUser", function (user) {
    socket.in("admin").emit("blockOrUnblockUser", user);
  });

  socket.on("deleteUser", function (user) {
    socket.in("admin").emit("deleteUser", user);
  });

  socket.on("loggedIn", function (user) {
    socket.join(user.id);
    if (user.type == "EC") {
      socket.join("chefs");
    }
    if (user.type == "ED") {
      socket.join("caixa");
    }
    if (user.type == "EM") {
      socket.join("admin");
    }
  });

  socket.on("loggedOut", function (user) {
    socket.leave(user.id);
    socket.leave("chefs");
    socket.leave("caixa");
  });
});
