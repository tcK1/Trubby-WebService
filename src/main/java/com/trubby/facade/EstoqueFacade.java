package com.trubby.facade;

import java.util.List;

import com.trubby.database.model.Estoque;

public interface EstoqueFacade {

	void incluirEstoque(Estoque estoque);
	
	void atualizarEstoque(Estoque estoque);
	
	void removerEstoque(Estoque estoque);
	
	List<Estoque> selecionarEstoqueUsuario(Long idUsuario);
}
