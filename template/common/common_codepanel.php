<?php
	if(!defined('IN_TEMPLATE'))
    {
      exit('Access denied');
    }
?>
<style type="text/css" media="screen">
    #editor { 
        width : 100%;
        font-size:14px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div id="editor"><?= htmlspecialchars("#include<iostream>
#include<algorithm>
#include<cmath>
using namespace std;
struct node{
	long long sum;
	long long size;
	long long color;
	long long etd;
	long long s()const{
		return sum+size*etd; 
	}
};
node d[400000] = {0,0,0,0};
int N,M;
#define LI(X) ((X)*2+1)
#define RI(X) ((X)*2+2)
#define rmq 1,N,0
void down(int I)
{
	if(d[I].color==-1)return ;
	if(d[I].size == 1){
		d[I].sum+=d[I].etd;
		d[I].etd = 0;
	}
	d[I].sum = d[I].s();
	d[LI(I)].etd+=d[I].etd;
	d[RI(I)].etd+=d[I].etd;
	d[I].etd = 0;
	d[LI(I)].color = d[I].color;
	d[RI(I)].color = d[I].color;
}
node merge(node a,node b)
{
	node c;
	c.size = a.size + b.size ;
	c.sum  = a.s()  + b.s();
	c.etd  = 0;
	c.color= (a.color==b.color)?a.color:-1;
	return c;
}
void update(int l,int r,int c,bool init,int L,int R,int I)
{
	
	if(l==L&&R==r&&d[I].color!=-1||l==L&&R==r&&l==r)
	{
		int add = abs( d[I].color - c );
		if(init){
			d[I].sum=d[I].etd=0;
		}
		else{
			d[I].etd+=add;
		}
		d[I].color=c;
		d[I].size =R-L+1;
		return ;
	}
	down(I);
	int M=(L+R)/2;
	if( r<=M ) 		update(l,r,c,init,L  ,M,LI(I));
	else if( M< l ) update(l,r,c,init,M+1,R,RI(I));
	else { 	update(l  ,M,c,init,L  ,M,LI(I)); 
			update(M+1,r,c,init,M+1,R,RI(I)); 
	}
	d[I] = merge(d[LI(I)],d[RI(I)]);
}

node query(int l,int r,int L,int R,int I)
{
	if(l==L&&r==R)return d[I];
	down(I);
	int M=(L+R)/2;
	if( r<=M )return query(l,r,L  ,M,LI(I));
	if( M< l )return query(l,r,M+1,R,RI(I));
	return merge(	query(l  ,M,L  ,M,LI(I)) ,  
					query(M+1,r,M+1,R,RI(I)) );
}

int main()
{
	ios::sync_with_stdio(false);
	cin.tie(0);
	cin>>N>>M;
	int a,b,c,d;
	for(int i=1;i<=N;++i)
		update(i,i,i,true,rmq);
	while(M--)
	{
		cin>>a;
		if(a==1)
		{
			cin>>b>>c>>d;
			update(b,c,d,false,rmq);
		}
		else //a==2
		{
			cin>>b>>c;
			node tmp = query(b,c,rmq);
			cout<<tmp.s()<<'\\n';
		}
	}
}") ?></div>
        </div>
    </div>
</div>

    
<script src="js/ace/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var editor = ace.edit("editor");
    editor.setTheme("ace/theme/twilight");
    editor.getSession().setMode("ace/mode/c_cpp");
    editor.setOptions({
        maxLines: 30//Infinity
    });
</script>