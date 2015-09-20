package com.trubby.facade.impl;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Component;

import com.trubby.database.dao.UsuariosDAO;
import com.trubby.database.model.Usuarios;
import com.trubby.facade.UsuariosFacade;

@Component
public class UsuariosFacadeImpl implements UsuariosFacade {

	@Autowired
	private UsuariosDAO usuariosDAO;
	
	@Override
	public void incluirUsuario(Usuarios usuarios) {
		usuariosDAO.insert(usuarios);
	}

	@Override
	public void atualizarUsuario(Usuarios usuarios) {
		usuariosDAO.update(usuarios);
	}

	@Override
	public void removerUsuario(Long idUsuario) {
		usuariosDAO.delete(Usuarios.class, idUsuario);
	}

	@Override
	public Usuarios selecionarUsuario(Long idUsuario) {
		return usuariosDAO.select(Usuarios.class, idUsuario);
	}
}
