package com.trubby.database.model;

import java.util.Date;

import javax.persistence.Column;
import javax.persistence.Entity;
import javax.persistence.EnumType;
import javax.persistence.Enumerated;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.Id;
import javax.persistence.Table;
import javax.persistence.UniqueConstraint;

import com.trubby.database.enums.EstoqueQuantTipos;

@Entity(name="estoque")
@Table(uniqueConstraints={@UniqueConstraint(columnNames={"id_estoque", "id_usuario"})})
public class Estoque {

	@Id
	@GeneratedValue(strategy = GenerationType.IDENTITY)
	@Column(name="id_estoque")
	private Long idEstoque;
	
	@Column(name="id_usuario")
	private Long idUsuario;
	
	@Column(name="nome")
	private String nome;
	
	@Column(name="quantidade")
	private Double quantidade;
	
	@Enumerated(EnumType.STRING)
	@Column(name="quantidade_tipo")
	private EstoqueQuantTipos quantidadeTipo;
	
	@Column(name="custo")
	private Double custo;
	
	@Column(name="data_modificacao")
	private Date dataModificacao;

	public Long getIdEstoque() {
		return idEstoque;
	}

	public void setIdEstoque(Long idEstoque) {
		this.idEstoque = idEstoque;
	}

	public Long getIdUsuario() {
		return idUsuario;
	}

	public void setIdUsuario(Long idUsuario) {
		this.idUsuario = idUsuario;
	}

	public String getNome() {
		return nome;
	}

	public void setNome(String nome) {
		this.nome = nome;
	}

	public Double getQuantidade() {
		return quantidade;
	}

	public void setQuantidade(Double quantidade) {
		this.quantidade = quantidade;
	}

	public EstoqueQuantTipos getQuantidadeTipo() {
		return quantidadeTipo;
	}

	public void setQuantidadeTipo(EstoqueQuantTipos quantidadeTipo) {
		this.quantidadeTipo = quantidadeTipo;
	}

	public Double getCusto() {
		return custo;
	}

	public void setCusto(Double custo) {
		this.custo = custo;
	}

	public Date getDataModificacao() {
		return dataModificacao;
	}

	public void setDataModificacao(Date dataModificacao) {
		this.dataModificacao = dataModificacao;
	}
}
