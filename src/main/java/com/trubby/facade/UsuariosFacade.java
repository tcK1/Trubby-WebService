package com.trubby.facade;

import com.trubby.database.model.Usuarios;

public interface UsuariosFacade {

	void incluirUsuario(Usuarios usuarios);
	
	void atualizarUsuario(Usuarios usuarios);
	
	void removerUsuario(Long idUsuario);
	
	Usuarios selecionarUsuario(Long idUsuario);
}
