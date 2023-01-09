const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"debugbar.openhandler":{"uri":"_debugbar\/open","methods":["GET","HEAD"]},"debugbar.clockwork":{"uri":"_debugbar\/clockwork\/{id}","methods":["GET","HEAD"]},"debugbar.assets.css":{"uri":"_debugbar\/assets\/stylesheets","methods":["GET","HEAD"]},"debugbar.assets.js":{"uri":"_debugbar\/assets\/javascript","methods":["GET","HEAD"]},"debugbar.cache.delete":{"uri":"_debugbar\/cache\/{key}\/{tags?}","methods":["DELETE"]},"index":{"uri":"\/","methods":["GET","HEAD"]},"export.index":{"uri":"export\/{user}","methods":["GET","HEAD"],"bindings":{"user":"id"}},"export.launch":{"uri":"export-launch\/{user}","methods":["GET","HEAD"],"bindings":{"user":"id"}},"admin.control-panel.create":{"uri":"control-panel\/create","methods":["GET","HEAD"]},"admin.control-panel.store":{"uri":"control-panel","methods":["POST"]},"admin.control-panel.edit":{"uri":"control-panel\/{user}\/edit","methods":["GET","HEAD"],"bindings":{"user":"id"}},"admin.control-panel.update":{"uri":"control-panel\/{user}","methods":["PUT"],"bindings":{"user":"id"}},"admin.control-panel.destroy":{"uri":"control-panel\/{user}","methods":["DELETE"],"bindings":{"user":"id"}},"admin.control-panel.restore":{"uri":"control-panel\/{user}\/restore","methods":["PUT"],"bindings":{"user":"id"}},"admin.control-panel.index":{"uri":"control-panel","methods":["GET","HEAD"]},"admin.projects.create":{"uri":"projects\/create","methods":["GET","HEAD"]},"admin.projects.store":{"uri":"projects","methods":["POST"]},"admin.projects.edit":{"uri":"projects\/{project}\/edit","methods":["GET","HEAD"],"bindings":{"project":"id"}},"admin.projects.update":{"uri":"projects\/{project}","methods":["PUT"],"bindings":{"project":"id"}},"admin.projects.destroy":{"uri":"projects\/{project}","methods":["DELETE"],"bindings":{"project":"id"}},"admin.projects.restore":{"uri":"projects\/{project}\/restore","methods":["PUT"],"bindings":{"project":"id"}},"admin.projects.invite":{"uri":"projects\/{project}\/invite","methods":["GET","HEAD"],"bindings":{"project":"id"}},"admin.projects.invite-store":{"uri":"projects\/{project}\/invite-store","methods":["POST"],"bindings":{"project":"id"}},"admin.projects.tasks.invite":{"uri":"projects\/{project}\/tasks\/{task}\/invite","methods":["GET","HEAD"],"bindings":{"project":"id","task":"id"}},"admin.projects.tasks.invite-store":{"uri":"projects\/{project}\/tasks\/{task}\/invite-store","methods":["POST"],"bindings":{"project":"id","task":"id"}},"user.projects.index":{"uri":"projects","methods":["GET","HEAD"]},"user.projects.tasks.index":{"uri":"projects\/{project}\/tasks","methods":["GET","HEAD"],"bindings":{"project":"id"}},"user.projects.tasks.create":{"uri":"projects\/{project}\/tasks\/create","methods":["GET","HEAD"],"bindings":{"project":"id"}},"user.projects.tasks.store":{"uri":"projects\/{project}\/tasks","methods":["POST"],"bindings":{"project":"id"}},"user.projects.tasks.edit":{"uri":"projects\/{project}\/tasks\/{task}\/edit","methods":["GET","HEAD"],"bindings":{"project":"id","task":"id"}},"user.projects.tasks.update":{"uri":"projects\/{project}\/tasks\/{task}","methods":["PUT"],"bindings":{"task":"id"}},"user.projects.tasks.destroy":{"uri":"projects\/{project}\/tasks\/{task}","methods":["DELETE"],"bindings":{"task":"id"}},"user.projects.tasks.restore":{"uri":"projects\/{project}\/tasks\/{task}\/restore","methods":["PUT"],"bindings":{"task":"id"}},"login":{"uri":"login","methods":["GET","HEAD"]},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"]},"password.email":{"uri":"forgot-password","methods":["POST"]},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"]},"password.update":{"uri":"reset-password","methods":["POST"]},"verification.notice":{"uri":"verify-email","methods":["GET","HEAD"]},"verification.verify":{"uri":"verify-email\/{id}\/{hash}","methods":["GET","HEAD"]},"verification.send":{"uri":"email\/verification-notification","methods":["POST"]},"password.confirm":{"uri":"confirm-password","methods":["GET","HEAD"]},"logout":{"uri":"logout","methods":["POST"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };